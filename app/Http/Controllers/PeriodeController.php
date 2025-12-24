<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::orderBy('start_date', 'desc')->get();
        $activePeriode = Periode::where('is_active', 1)->first();

        return view('periode.index', compact('periodes', 'activePeriode'));
    }

    public function create()
    {
        $previousPeriodes = Periode::orderBy('start_date', 'desc')->get();

        $suggestions = [
            'name'       => "Evaluasi " . Carbon::now()->format('F Y'),
            'code'       => "PER" . Carbon::now()->format('Ym'),
            'start_date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'end_date'   => Carbon::now()->endOfMonth()->format('Y-m-d')
        ];

        // Ambil semua supplier unik dari semua periode
        $allSuppliers = Supplier::select('id', 'code', 'name', 'location', 'price_per_kg', 
                                        'volume_per_month', 'on_time_percent', 'freq_per_month', 'periode_id')
            ->with('periode')
            ->orderBy('code')
            ->get()
            ->unique('code'); // Ambil unique berdasarkan code

        $criteriaTemplates = Criteria::with('parameters')
            ->whereHas('parameters')
            ->get();

        return view('periode.create', [
            'periode'                 => null,
            'previousPeriodes'        => $previousPeriodes,
            'suggestions'             => $suggestions,
            'allSuppliers'            => $allSuppliers,
            'criteriaTemplates'       => $criteriaTemplates
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'name'              => 'required|string|max:255',
        'code'              => 'required|string|max:100|unique:periodes,code',
        'start_date'        => 'required|date',
        'end_date'          => 'required|date|after:start_date',
        'selected_suppliers' => 'required|array|min:1',
        'selected_suppliers.*' => 'exists:suppliers,id',
        'supplier_data'     => 'required|array',
        'supplier_data.*.price_per_kg' => 'required|numeric|min:0',
        'supplier_data.*.volume_per_month' => 'required|numeric|min:0',
        'supplier_data.*.on_time_percent' => 'required|numeric|min:0|max:100',
        'supplier_data.*.freq_per_month' => 'required|integer|min:0',
    ]);

    $dayCount = Carbon::parse($request->start_date)->diffInDays($request->end_date);
    if ($dayCount > 31) {
        return back()->withInput()->with('error', 'Periode maksimal 31 hari!');
    }

    DB::beginTransaction();
    try {
        Periode::query()->update(['is_active' => 0]);

        $periode = Periode::create([
            'name'        => $request->name,
            'code'        => $request->code,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'description' => $request->description,
            'is_active'   => true,
        ]);

        // Buat kriteria default
        $this->createDefaultCriteria($periode->id);

        // Copy supplier dengan data baru
        if ($request->selected_suppliers && count($request->selected_suppliers) > 0) {
            $this->copySuppliersWithNewData($request->selected_suppliers, $request->supplier_data, $periode->id);
        }

        DB::commit();

        return redirect()->route('periode.index')
            ->with('success', 'Periode baru dibuat & otomatis aktif!');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Gagal membuat periode: ' . $e->getMessage());
    }
}

private function copySuppliersWithNewData(array $supplierIds, array $supplierData, $toPeriodeId)
{
    $suppliers = Supplier::whereIn('id', $supplierIds)->get();

    foreach ($suppliers as $s) {
        // Cek jika supplier dengan code yang sama sudah ada di periode tujuan
        $existing = Supplier::where('periode_id', $toPeriodeId)
            ->where('code', $s->code)
            ->exists();

        if (!$existing) {
            Supplier::create([
                'periode_id'        => $toPeriodeId,
                'code'              => $s->code,
                'name'              => $s->name,
                'location'          => $s->location,
                'price_per_kg'      => $supplierData[$s->id]['price_per_kg'] ?? $s->price_per_kg,
                'volume_per_month'  => $supplierData[$s->id]['volume_per_month'] ?? $s->volume_per_month,
                'on_time_percent'   => $supplierData[$s->id]['on_time_percent'] ?? $s->on_time_percent,
                'freq_per_month'    => $supplierData[$s->id]['freq_per_month'] ?? $s->freq_per_month,
            ]);
        }
    }
}

    public function edit(Periode $periode)
    {
        $previousPeriodes = Periode::where('id', '!=', $periode->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('periode.edit', [
            'periode'          => $periode,
            'previousPeriodes' => $previousPeriodes
        ]);
    }

    public function update(Request $request, Periode $periode)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'code'       => 'required|string|max:100|unique:periodes,code,' . $periode->id,
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after:start_date',
    ]);

    // Jika periode tidak aktif, boleh edit data supplier dan kriteria
    if (!$periode->is_active) {
        $request->validate([
            'suppliers' => 'nullable|array',
            'suppliers.*.price_per_kg' => 'required|numeric|min:0',
            'suppliers.*.volume_per_month' => 'required|numeric|min:0',
            'suppliers.*.on_time_percent' => 'required|numeric|min:0|max:100',
            'suppliers.*.freq_per_month' => 'required|integer|min:0',
            'criterias' => 'nullable|array',
            'criterias.*.percentage' => 'nullable|numeric|min:0|max:100',
            'criterias.*.type' => 'nullable|in:Benefit,Cost',
            'parameters' => 'nullable|array',
            'parameters.*.score' => 'nullable|integer|min:1|max:5',
            'parameters.*.description' => 'nullable|string',
            'parameters.*.operator' => 'nullable|in:lte,gte,equal,between',
            'parameters.*.min_value' => 'nullable|numeric',
            'parameters.*.max_value' => 'nullable|numeric',
        ]);
    }

    $dayCount = Carbon::parse($request->start_date)->diffInDays($request->end_date);
    if ($dayCount > 31) {
        return back()->withInput()->with('error', 'Periode maksimal 31 hari!');
    }

    DB::beginTransaction();
    try {
        // Update periode
        $periode->update([
            'name'        => $request->name,
            'code'        => $request->code,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'description' => $request->description,
        ]);

        // Update data supplier jika periode tidak aktif
        if (!$periode->is_active && $request->suppliers) {
            foreach ($request->suppliers as $supplierId => $supplierData) {
                $supplier = Supplier::find($supplierId);
                if ($supplier && $supplier->periode_id == $periode->id) {
                    $supplier->update($supplierData);
                }
            }
        }

        // Update kriteria jika periode tidak aktif
        if (!$periode->is_active && $request->criterias) {
            foreach ($request->criterias as $criteriaId => $criteriaData) {
                $criteria = Criteria::find($criteriaId);
                if ($criteria && $criteria->periode_id == $periode->id) {
                    $criteria->update($criteriaData);
                }
            }
        }

        // Update parameter jika periode tidak aktif
        if (!$periode->is_active && $request->parameters) {
            foreach ($request->parameters as $parameterId => $parameterData) {
                $parameter = Parameter::find($parameterId);
                if ($parameter) {
                    // Cek apakah parameter milik kriteria di periode ini
                    $criteria = $parameter->criteria;
                    if ($criteria && $criteria->periode_id == $periode->id) {
                        $parameter->update($parameterData);
                    }
                }
            }
        }

        DB::commit();

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil diperbarui!');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Gagal memperbarui periode: ' . $e->getMessage());
    }
}

    public function setActive(Periode $periode)
    {
        Periode::query()->update(['is_active' => 0]);
        $periode->update(['is_active' => 1]);

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil diaktifkan!');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_active) {
            return redirect()->route('periode.index')
                ->with('error', 'Tidak dapat menghapus periode aktif!');
        }

        $periode->delete();

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil dihapus!');
    }

    public function generateNextMonth()
    {
        $last = Periode::orderBy('end_date', 'desc')->first();
        $nextMonth = $last ? Carbon::parse($last->end_date)->addMonth() : Carbon::now()->addMonth();

        return redirect()->route('periode.create')
            ->withInput([
                'name'       => "Evaluasi " . $nextMonth->translatedFormat('F Y'),
                'code'       => "PER" . $nextMonth->format('Ym'),
                'start_date' => $nextMonth->startOfMonth()->format('Y-m-d'),
                'end_date'   => $nextMonth->endOfMonth()->format('Y-m-d'),
            ]);
    }

    private function createDefaultCriteria($periodeId)
    {
        $default = [
            [
                'kode'       => 'C1',
                'name'       => 'Harga (Rp/kg)',
                'weight'     => 0.3,
                'percentage' => 30,
                'type'       => 'Cost',
                'slug'       => 'harga-rpkg',
                'params'     => [
                    ['score'=>5,'operator'=>'lte','min'=>null,'max'=>180,'desc'=>'≤180'],
                    ['score'=>4,'operator'=>'between','min'=>181,'max'=>184,'desc'=>'181–184'],
                    ['score'=>3,'operator'=>'between','min'=>185,'max'=>189,'desc'=>'185–189'],
                    ['score'=>2,'operator'=>'between','min'=>190,'max'=>194,'desc'=>'190–194'],
                    ['score'=>1,'operator'=>'gte','min'=>195,'max'=>null,'desc'=>'≥195'],
                ]
            ],
            [
                'kode'       => 'C2',
                'name'       => 'Volume Pasokan (kg/bulan)',
                'weight'     => 0.25,
                'percentage' => 25,
                'type'       => 'Benefit',
                'slug'       => 'volume-pasokan',
                'params'     => [
                    ['score'=>5,'operator'=>'gte','min'=>15000,'max'=>null,'desc'=>'≥15000'],
                    ['score'=>4,'operator'=>'between','min'=>10000,'max'=>14999,'desc'=>'10000–14999'],
                    ['score'=>3,'operator'=>'between','min'=>7000,'max'=>9999,'desc'=>'7000–9999'],
                    ['score'=>2,'operator'=>'between','min'=>4000,'max'=>6999,'desc'=>'4000–6999'],
                    ['score'=>1,'operator'=>'lte','min'=>null,'max'=>3999,'desc'=>'<4000'],
                ]
            ],
            [
                'kode'       => 'C3',
                'name'       => 'Ketepatan Waktu (%)',
                'weight'     => 0.25,
                'percentage' => 25,
                'type'       => 'Benefit',
                'slug'       => 'ketepatan-waktu',
                'params'     => [
                    ['score'=>5,'operator'=>'gte','min'=>100,'max'=>null,'desc'=>'100%'],
                    ['score'=>4,'operator'=>'between','min'=>90,'max'=>99,'desc'=>'90–99%'],
                    ['score'=>3,'operator'=>'between','min'=>75,'max'=>89,'desc'=>'75–89%'],
                    ['score'=>2,'operator'=>'between','min'=>50,'max'=>74,'desc'=>'50–74%'],
                    ['score'=>1,'operator'=>'lte','min'=>null,'max'=>49,'desc'=>'<50%'],
                ]
            ],
            [
                'kode'       => 'C4',
                'name'       => 'Frekuensi Pengiriman (kali/bulan)',
                'weight'     => 0.2,
                'percentage' => 20,
                'type'       => 'Benefit',
                'slug'       => 'frekuensi-pengiriman',
                'params'     => [
                    ['score'=>5,'operator'=>'gte','min'=>4,'max'=>null,'desc'=>'≥4'],
                    ['score'=>4,'operator'=>'equal','min'=>3,'max'=>3,'desc'=>'3'],
                    ['score'=>3,'operator'=>'equal','min'=>2,'max'=>2,'desc'=>'2'],
                    ['score'=>2,'operator'=>'equal','min'=>1,'max'=>1,'desc'=>'1'],
                    ['score'=>1,'operator'=>'equal','min'=>0,'max'=>0,'desc'=>'0'],
                ]
            ],
        ];

        foreach ($default as $c) {
            $criteria = Criteria::create([
                'periode_id' => $periodeId,
                'code'       => $c['kode'],
                'kode'       => $c['kode'],
                'name'       => $c['name'],
                'weight'     => $c['weight'],
                'percentage' => $c['percentage'],
                'type'       => $c['type'],
                'slug'       => $c['slug'],
            ]);

            foreach ($c['params'] as $p) {
                Parameter::create([
                    'criteria_id' => $criteria->id,
                    'score'       => $p['score'],
                    'operator'    => $p['operator'],
                    'min_value'   => $p['min'],
                    'max_value'   => $p['max'],
                    'description' => $p['desc'],
                ]);
            }
        }
    }

    private function copySelectedSuppliers(array $supplierIds, $toPeriodeId)
    {
        $suppliers = Supplier::whereIn('id', $supplierIds)->get();

        foreach ($suppliers as $s) {
            // Cek jika supplier dengan code yang sama sudah ada di periode tujuan
            $existing = Supplier::where('periode_id', $toPeriodeId)
                ->where('code', $s->code)
                ->exists();

            if (!$existing) {
                Supplier::create([
                    'periode_id'        => $toPeriodeId,
                    'code'              => $s->code,
                    'name'              => $s->name,
                    'location'          => $s->location,
                    'price_per_kg'      => $s->price_per_kg,
                    'volume_per_month'  => $s->volume_per_month,
                    'on_time_percent'   => $s->on_time_percent,
                    'freq_per_month'    => $s->freq_per_month,
                ]);
            }
        }
    }

    private function copyCriteriaFromTemplate($templateId, $toPeriodeId)
    {
        $template = Criteria::with('parameters')->find($templateId);
        
        if ($template) {
            $new = Criteria::create([
                'periode_id' => $toPeriodeId,
                'code'       => $template->code ?? $template->kode,
                'kode'       => $template->kode,
                'name'       => $template->name,
                'weight'     => $template->weight,
                'percentage' => $template->percentage ?? intval($template->weight * 100),
                'type'       => $template->type ?? 'Benefit',
                'slug'       => $template->slug ?? strtolower(str_replace(' ', '-', $template->name)),
            ]);

            foreach ($template->parameters as $p) {
                Parameter::create([
                    'criteria_id' => $new->id,
                    'score'       => $p->score,
                    'operator'    => $p->operator ?? 'between',
                    'min_value'   => $p->min_value,
                    'max_value'   => $p->max_value,
                    'description' => $p->description,
                ]);
            }
        }
    }
}