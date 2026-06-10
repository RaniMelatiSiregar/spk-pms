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

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $content = file_get_contents($file->getRealPath());

        // Hapus BOM jika ada
        $content = ltrim($content, "\xEF\xBB\xBF");

        $lines = preg_split('/\r\n|\r|\n/', trim($content));

        if (empty($lines)) {
            return back()->with('error', 'File CSV kosong');
        }

        // Deteksi separator otomatis dari baris header
        $header = $lines[0];
        if (substr_count($header, "\t") >= substr_count($header, ",") && 
            substr_count($header, "\t") >= substr_count($header, ";")) {
            $separator = "\t";
        } elseif (substr_count($header, ";") >= substr_count($header, ",")) {
            $separator = ";";
        } else {
            $separator = ",";
        }

        // Buang baris header
        array_shift($lines);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $row = str_getcsv($line, $separator);

            if (count($row) < 2) {
                $errors[] = "Baris " . ($index + 2) . ": format tidak valid";
                $skipped++;
                continue;
            }

            $code     = trim($row[0] ?? '');
            $name     = trim($row[1] ?? '');
            $location = trim($row[2] ?? '');

            if (empty($code) || empty($name)) {
                $errors[] = "Baris " . ($index + 2) . ": kode atau nama kosong";
                $skipped++;
                continue;
            }

            if (Supplier::where('code', $code)->exists()) {
                $errors[] = "Baris " . ($index + 2) . ": kode '$code' sudah ada, dilewati";
                $skipped++;
                continue;
            }

            Supplier::create([
                'code'     => $code,
                'name'     => $name,
                'location' => $location ?: null,
            ]);

            $imported++;
        }

        $message = "$imported supplier berhasil diimport";
        if ($skipped > 0) {
            $message .= ", $skipped dilewati";
        }

        return redirect()
            ->route('supplier.index')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }
}