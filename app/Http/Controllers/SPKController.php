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

    private function utility($value, $min, $max)
{
    if ($max == $min) return 1;
    return round(1 + 4 * (($value - $min) / ($max - $min)), 2);
}

public function calculateSMART($periode_id)
{
    $suppliers = Supplier::where('periode_id', $periode_id)->get();
    $criterias = Criteria::where('periode_id', $periode_id)->get();

    $stats = [
        'price_per_kg'     => [$suppliers->min('price_per_kg'), $suppliers->max('price_per_kg')],
        'volume_per_month' => [$suppliers->min('volume_per_month'), $suppliers->max('volume_per_month')],
        'on_time_percent'  => [$suppliers->min('on_time_percent'), $suppliers->max('on_time_percent')],
        'freq_per_month'   => [$suppliers->min('freq_per_month'), $suppliers->max('freq_per_month')],
    ];

    $results = [];

    foreach ($suppliers as $s) {

        $detail = [];
        $total = 0;

        foreach ($criterias as $c) {

            if (str_contains($c->name, 'Harga')) {
                $u = 6 - $this->utility($s->price_per_kg, ...$stats['price_per_kg']);
            }
            elseif (str_contains($c->name, 'Volume')) {
                $u = $this->utility($s->volume_per_month, ...$stats['volume_per_month']);
            }
            elseif (str_contains($c->name, 'Ketepatan')) {
                $u = $this->utility($s->on_time_percent, ...$stats['on_time_percent']);
            }
            elseif (str_contains($c->name, 'Frekuensi')) {
                $u = $this->utility($s->freq_per_month, ...$stats['freq_per_month']);
            } else continue;

            $weighted = $u * $c->weight;
            $total += $weighted;

            $detail[$c->code] = [
                'score' => $u,
                'weighted' => round($weighted, 3)
            ];
        }

        $results[] = [
            'supplier' => $s,
            'score' => round($total, 3),
            'detail' => $detail
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
