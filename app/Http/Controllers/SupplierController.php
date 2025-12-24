<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Periode;

class SupplierController extends Controller
{
    public function index()
    {
        $periode = Periode::where('is_active', 1)->first();

        if (!$periode) {
            return view('dashboard.suppliers.index', [
                'suppliers' => collect([])
            ]);
        }

        $suppliers = Supplier::where('periode_id', $periode->id)->get();

        return view('dashboard.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('dashboard.suppliers.form', ['supplier' => null]);
    }

    public function store(Request $r)
    {
        $r->validate([
            'code' => 'required|unique:suppliers,code',
            'name' => 'required',
            'price_per_kg' => 'required|integer',
            'volume_per_month' => 'required|integer',
            'on_time_percent' => 'required|integer',
            'freq_per_month' => 'required|integer',
        ]);

        $periode = Periode::where('is_active', 1)->first();

        Supplier::create([
            'code' => $r->code,
            'name' => $r->name,
            'location' => $r->location,
            'price_per_kg' => $r->price_per_kg,
            'volume_per_month' => $r->volume_per_month,
            'on_time_percent' => $r->on_time_percent,
            'freq_per_month' => $r->freq_per_month,
            'periode_id' => $periode?->id,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier ditambah');
    }

    public function edit(Supplier $supplier)
    {
        return view('dashboard.suppliers.form', compact('supplier'));
    }

    public function update(Request $r, Supplier $supplier)
    {
        $r->validate([
            'code' => "required|unique:suppliers,code,{$supplier->id}",
            'name' => 'required',
            'price_per_kg' => 'required|integer',
            'volume_per_month' => 'required|integer',
            'on_time_percent' => 'required|integer',
            'freq_per_month' => 'required|integer',
        ]);

        $supplier->update($r->all());

        return redirect()->route('supplier.index')->with('success','Supplier diupdate');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success','Supplier dihapus');
    }
}
