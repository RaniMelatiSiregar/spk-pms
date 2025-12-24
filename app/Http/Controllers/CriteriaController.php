<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Periode;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $periode = Periode::where('is_active', 1)->first();

        $criterias = Criteria::with('parameters')
            ->where('periode_id', $periode->id)
            ->get();

        return view('dashboard.criteria.index', compact('criterias', 'periode'));
    }

    public function create()
    {
        $periode = Periode::where('is_active', 1)->first();
        $criteria = null;

        return view('dashboard.criteria.form', compact('periode', 'criteria'));
    }

    public function store(Request $request)
    {
        $periode = Periode::where('is_active', 1)->first();

        $nextCode = "C" . (Criteria::where('periode_id', $periode->id)->count() + 1);

        Criteria::create([
            'periode_id' => $periode->id,
            'code'       => $nextCode,
            'name'       => $request->name,
            'type'       => $request->type,
            'weight'     => $request->weight,
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criteria $kriteria)
    {
        $criteria = $kriteria;
        return view('dashboard.criteria.form', compact('criteria'));
    }

    public function update(Request $request, Criteria $kriteria)
    {
        $kriteria->update([
            'name'   => $request->name,
            'type'   => $request->type,
            'weight' => $request->weight,
        ]);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $kriteria)
    {
        $kriteria->delete();
        return back()->with('success', 'Kriteria berhasil dihapus.');
    }
}
