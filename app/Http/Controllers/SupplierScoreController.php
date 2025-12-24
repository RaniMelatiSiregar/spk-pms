<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Parameter;
use App\Models\SupplierScore;
use Illuminate\Http\Request;

class SupplierScoreController extends Controller
{
    public function index(Supplier $supplier)
    {
        $criterias = Criteria::where('periode_id', $supplier->periode_id)
            ->with('parameters')
            ->get();

        $scores = SupplierScore::where('supplier_id', $supplier->id)->get()->keyBy('criteria_id');

        return view('scores.index', compact('supplier', 'criterias', 'scores'));
    }

    public function store(Request $request, Supplier $supplier)
    {
        foreach ($request->criteria as $criteriaId => $value) {
            $param = Parameter::find($value['parameter_id']);

            SupplierScore::updateOrCreate(
                [
                    'supplier_id' => $supplier->id,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'parameter_id' => $value['parameter_id'],
                    'raw_value'    => $value['raw_value'],
                    'score'        => $param?->score,
                ]
            );
        }

        return back()->with('success', 'Nilai supplier berhasil disimpan');
    }
}
