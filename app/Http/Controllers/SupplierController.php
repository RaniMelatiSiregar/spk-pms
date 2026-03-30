<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Periode;
use App\Models\Criteria;
use App\Models\SupplierScore;

class SupplierController extends Controller
{
    public function index()
    {

        $suppliers = Supplier::with('periode')
            ->orderBy('created_at', 'asc')
            ->get();

        $activePeriode = Periode::where('is_active', 1)->first();

        if ($activePeriode) {

            $criteriaIds = Criteria::where('periode_id', $activePeriode->id)
                ->pluck('id');

            $activeSupplierIds = SupplierScore::whereIn('criteria_id', $criteriaIds)
                ->pluck('supplier_id')
                ->unique()
                ->toArray();

            $activeSuppliersCount = count($activeSupplierIds);

        } else {
            $activeSupplierIds = [];
            $activeSuppliersCount = 0;
        }

        return view('dashboard.suppliers.index', compact(
            'suppliers',
            'activeSuppliersCount',
            'activeSupplierIds'
        ));

    }

    public function create()
    {
        return view('dashboard.suppliers.form', ['supplier' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:suppliers,code',
            'name' => 'required',
        ]);

        Supplier::create([
            'code' => $request->code,
            'name' => $request->name,
            'location' => $request->location,
            // periode & penilaian diisi saat buat periode
        ]);

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        return view('dashboard.suppliers.form', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'code' => 'required|unique:suppliers,code,' . $supplier->id,
            'name' => 'required',
        ]);

        $supplier->update([
            'code' => $request->code,
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}