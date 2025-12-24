<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Periode;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SPKExport;
use PDF;

class SPKController extends Controller
{
    private function matchParameter($criteria, $value)
    {
        foreach ($criteria->parameters as $p) {

            $op  = strtolower($p->operator ?? 'between');
            $min = is_numeric($p->min_value) ? floatval($p->min_value) : PHP_FLOAT_MIN;
            $max = is_numeric($p->max_value) ? floatval($p->max_value) : PHP_FLOAT_MAX;

            switch ($op) {

                case '<=':
                case 'lte':
                    if ($value <= $max) return $p->score;
                    break;

                case '>=':
                case 'gte':
                    if ($value >= $min) return $p->score;
                    break;

                case '=':
                case 'equal':
                    if ($value == $min) return $p->score;
                    break;

                case 'between':
                default:
                    if ($value >= $min && $value <= $max) return $p->score;
                    break;
            }
        }

        return 1; // default worst score
    }

    private function getSupplierValue($supplier, $criteriaName)
    {
        $map = [
            'Harga'     => 'price_per_kg',
            'Volume'    => 'volume_per_month',
            'Ketepatan' => 'on_time_percent',
            'Frekuensi' => 'freq_per_month'
        ];

        foreach ($map as $key => $field) {
            if (stripos($criteriaName, $key) !== false) {
                $value = $supplier->$field ?? 0;
                return is_numeric($value) ? floatval($value) : 0;
            }
        }

        return 0;
    }

    public function calculateSMART($periode_id)
    {
        $suppliers = Supplier::where('periode_id', $periode_id)->get();
        $criterias = Criteria::with('parameters')
            ->where('periode_id', $periode_id)
            ->orderBy('id')
            ->get();

        if ($suppliers->isEmpty() || $criterias->isEmpty()) {
            return collect([]);
        }

        $results = [];

        foreach ($suppliers as $supplier) {
            $totalScore = 0;
            $detail = [];

            foreach ($criterias as $c) {

                $value = $this->getSupplierValue($supplier, $c->name);
                $score = $this->matchParameter($c, $value);
                $weighted = $score * $c->weight;

                $totalScore += $weighted;

                $detail[$c->code] = [
                    'value'    => $value,
                    'score'    => $score,
                    'weighted' => round($weighted, 4)
                ];
            }

            $results[] = [
                'supplier' => $supplier,
                'score'    => round($totalScore, 4),
                'detail'   => $detail
            ];
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return collect($results);
    }

    public function compute($periodeId = null)
    {
        $periode = $periodeId
            ? Periode::findOrFail($periodeId)
            : Periode::where('is_active', 1)->first();

        if (!$periode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $results = $this->calculateSMART($periode->id);

        return view('dashboard.spk.compute', [
            'periode'      => $periode,
            'results'      => $results,
            'criteriaList' => Criteria::with('parameters')->where('periode_id', $periode->id)->get(),
            'suppliers'    => Supplier::where('periode_id', $periode->id)->get()
        ]);
    }

    public function result(Request $request)
    {
        $periode = Periode::where('is_active', 1)->first();
        if (!$periode) return back()->with('error', 'Tidak ada periode aktif.');

        $results = $this->calculateSMART($periode->id);

        return view('dashboard.spk.result', compact('results', 'periode'));
    }

    public function history(Request $request)
    {
        $periode_id = $request->periode_id ?? Periode::latest()->value('id');

        $periodes = Periode::orderBy('start_date', 'desc')->get();

        $results = $this->calculateSMART($periode_id);

        return view('dashboard.spk.history', compact('results', 'periodes', 'periode_id'));
    }

    public function exportPDF()
    {
        $periode = Periode::where('is_active', 1)->first();
        if (!$periode) return back()->with('error', 'Tidak ada periode aktif.');

        $results = $this->calculateSMART($periode->id);

        $criteriaList = Criteria::where('periode_id', $periode->id)->orderBy('id')->get();

        $pdf = PDF::loadView('dashboard.spk.pdf', [
            'periode'      => $periode,
            'results'      => $results,
            'criteriaList' => $criteriaList
        ]);

        return $pdf->download('hasil_spk.pdf');
    }

    public function exportExcel()
    {
        $periode = Periode::where('is_active', 1)->first();
        if (!$periode) return back()->with('error', 'Tidak ada periode aktif.');

        return Excel::download(new SPKExport($periode->id), 'hasil_spk.xlsx');
    }
}
