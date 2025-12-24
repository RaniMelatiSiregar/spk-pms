<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Periode;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodes = Periode::orderBy('start_date', 'desc')->get();

        if ($request->periode_id) {
            $selectedPeriode = Periode::find($request->periode_id);
        } else {
            $selectedPeriode = Periode::where('is_active', 1)->first();
        }

        if (!$selectedPeriode) {
            $selectedPeriode = $periodes->first();
        }

        if (!$selectedPeriode) {
            return view('dashboard.index', [
                'suppliers' => collect([]),
                'criterias' => collect([]),
                'periodes' => collect([]),
                'selectedPeriode' => null,
                'summary' => [
                    'total_suppliers' => 0,
                    'avg_price' => 0,
                    'avg_volume' => 0,
                    'avg_on_time' => 0,
                ]
            ]);
        }

        $suppliers = Supplier::where('periode_id', $selectedPeriode->id)->get();
        $criterias = Criteria::where('periode_id', $selectedPeriode->id)->get();

        $summary = [
            'total_suppliers' => $suppliers->count(),
            'avg_price' => round($suppliers->avg('price_per_kg') ?? 0, 2),
            'avg_volume' => round($suppliers->avg('volume_per_month') ?? 0, 2),
            'avg_on_time' => round($suppliers->avg('on_time_percent') ?? 0, 2),
        ];

        return view('dashboard.index', compact(
            'suppliers',
            'criterias',
            'periodes',
            'selectedPeriode',
            'summary'
        ));
    }
}
