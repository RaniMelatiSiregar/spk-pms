<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parameter;
use App\Models\Criteria;

class ParameterController extends Controller
{
    public function index(Criteria $kriteria)
    {
        $parameters = $kriteria->parameters()->orderBy('score', 'desc')->get();
        return view('dashboard.parameter.index', compact('kriteria', 'parameters'));
    }

    public function create(Criteria $kriteria)
    {
        return view('dashboard.parameter.form', [
            'kriteria' => $kriteria,
            'parameter' => null
        ]);
    }

    public function store(Request $r, Criteria $kriteria)
    {
        $r->validate([
            'operator' => 'required|in:<=,>=,=,between,<,>',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'description' => 'nullable|string'
        ]);

        $score = Parameter::where('criteria_id', $kriteria->id)->max('score');
        $nextScore = $score ? $score - 1 : 5;

        Parameter::create([
            'criteria_id' => $kriteria->id,
            'score' => $nextScore,
            'operator' => $r->operator,
            'min_value' => $r->min_value,
            'max_value' => $r->max_value,
            'description' => $r->description
        ]);

        return redirect()->route('parameter.index', $kriteria)
            ->with('success', 'Parameter berhasil ditambahkan');
    }

    public function edit(Criteria $kriteria, Parameter $parameter)
    {
        return view('dashboard.parameter.form', compact('kriteria', 'parameter'));
    }

    public function update(Request $r, Criteria $kriteria, Parameter $parameter)
    {
        $r->validate([
            'score' => 'required|integer|min:1|max:5',
            'operator' => 'required|in:<=,>=,=,between,<,>',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'description' => 'nullable|string'
        ]);

        $parameter->update([
            'score' => $r->score,
            'operator' => $r->operator,
            'min_value' => $r->min_value,
            'max_value' => $r->max_value,
            'description' => $r->description
        ]);

        return redirect()->route('parameter.index', $kriteria)
            ->with('success', 'Parameter berhasil diperbarui');
    }

    public function destroy(Criteria $kriteria, Parameter $parameter)
    {
        $parameter->delete();

        return redirect()->route('parameter.index', $kriteria)
            ->with('success', 'Parameter berhasil dihapus');
    }
}
