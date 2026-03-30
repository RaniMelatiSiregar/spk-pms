<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Periode;
use App\Models\SupplierScore;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SPKExport;
use PDF;

class SPKController extends Controller
{
    private function linearRegressionPredict(array $scores): float
    {
        $n = count($scores);
        if ($n < 2) return round($scores[0] ?? 0, 3);

        $x = range(1, $n);

        $sumX = array_sum($x);
        $sumY = array_sum($scores);

        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $scores[$i];
            $sumX2 += $x[$i] ** 2;
        }

        $b = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX ** 2);
        $a = ($sumY - $b * $sumX) / $n;

        $pred = $a + $b * ($n + 1);

        return round(max(1, min(5, $pred)), 3);
    }

    private function getHistoricalScores($currentScore): array
    {
        return [
            round($currentScore * 0.95, 3),
            $currentScore
        ];
    }

    private function generateScoresIfEmpty($periode_id)
    {
        $criterias = Criteria::with('parameters')
            ->where('periode_id', $periode_id)
            ->get();

        $criteriaIds = $criterias->pluck('id');

        $existing = SupplierScore::whereIn('criteria_id', $criteriaIds)->count();

        if ($existing > 0) return;

        $suppliers = Supplier::where('periode_id', $periode_id)->get();

        foreach ($suppliers as $supplier) {

            foreach ($criterias as $c) {

                if (str_contains($c->name, 'Harga')) {
                    $value = $supplier->price_per_kg ?? 0;
                } elseif (str_contains($c->name, 'Volume')) {
                    $value = $supplier->volume_per_month ?? 0;
                } elseif (str_contains($c->name, 'Ketepatan')) {
                    $value = $supplier->on_time_percent ?? 0;
                } elseif (str_contains($c->name, 'Frekuensi')) {
                    $value = $supplier->freq_per_month ?? 0;
                } else {
                    continue;
                }

                $param = $c->parameters->first(function ($p) use ($value) {
                    $min = $p->min_value ?? -INF;
                    $max = $p->max_value ?? INF;

                    if ($p->operator == 'lte') return $value <= $max;
                    if ($p->operator == 'gte') return $value >= $min;
                    if ($p->operator == 'equal') return $value == $min;

                    return $value >= $min && $value <= $max;
                });

                SupplierScore::create([
                    'supplier_id' => $supplier->id,
                    'criteria_id' => $c->id,
                    'parameter_id'=> $param?->id,
                    'raw_value'   => $value,
                    'score'       => $param?->score ?? 1,
                ]);
            }
        }
    }

    public function calculateSMART($periode_id)
    {
        $this->generateScoresIfEmpty($periode_id);

        $criterias = Criteria::where('periode_id', $periode_id)->get();

        if ($criterias->count() == 0) {
            return collect([]);
        }

        $criteriaIds = $criterias->pluck('id');

        $scores = SupplierScore::whereIn('criteria_id', $criteriaIds)->get();

        if ($scores->count() == 0) {
            return collect([]);
        }

        $grouped = $scores->groupBy('supplier_id');

        $supplierIds = $grouped->keys();

        $suppliers = Supplier::whereIn('id', $supplierIds)->get()->keyBy('id');

        $results = [];

        foreach ($grouped as $supplierId => $supplierScores) {

            if (!isset($suppliers[$supplierId])) continue;

            $total = 0;

            foreach ($criterias as $c) {

                $scoreRow = $supplierScores->firstWhere('criteria_id', $c->id);

                $score = $scoreRow->score ?? 0;

                $total += $score * $c->weight;
            }

            $finalScore = round($total, 3);

            $history = $this->getHistoricalScores($finalScore);
            $predicted = $this->linearRegressionPredict($history);

            $diff = $predicted - $finalScore;

            if ($diff > 0.05) $trend = 'naik';
            elseif ($diff < -0.05) $trend = 'turun';
            else $trend = 'stabil';

            $results[] = [
                'supplier'        => $suppliers[$supplierId],
                'score'           => $finalScore,
                'predicted_score' => $predicted,
                'trend'           => $trend,
                'history'         => $history,
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
            'suppliers'    => Supplier::whereIn('id', $results->pluck('supplier.id'))->get()
        ]);
    }

    public function result()
    {
        $periode = Periode::where('is_active', 1)->first();

        if (!$periode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $results = $this->calculateSMART($periode->id);

        return view('dashboard.spk.result', compact('results', 'periode'));
    }

    public function history(Request $request)
    {
        $periode_id = $request->periode_id ?? Periode::latest()->value('id');
        $periodes   = Periode::orderBy('start_date', 'desc')->get();
        $results    = $this->calculateSMART($periode_id);

        return view('dashboard.spk.history', compact('results', 'periodes', 'periode_id'));
    }

    public function exportPDF()
    {
        $periode = Periode::where('is_active', 1)->first();

        if (!$periode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $results      = $this->calculateSMART($periode->id);
        $criteriaList = Criteria::where('periode_id', $periode->id)->get();

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

        if (!$periode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        return Excel::download(new SPKExport($periode->id), 'hasil_spk.xlsx');
    }
}