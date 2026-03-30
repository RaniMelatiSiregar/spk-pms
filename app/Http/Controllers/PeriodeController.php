<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Parameter;
use App\Models\SupplierScore;
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

        $allSuppliers = Supplier::orderBy('code')->get();

        $criteriaTemplates = Criteria::with('parameters')
            ->whereHas('parameters')
            ->get();

        return view('periode.create', compact(
            'previousPeriodes',
            'suggestions',
            'allSuppliers',
            'criteriaTemplates'
        ));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:100|unique:periodes,code',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'selected_suppliers' => 'required|array|min:1',
        'supplier_data' => 'required|array',
    ]);

    DB::beginTransaction();

    try {
        Periode::query()->update(['is_active' => 0]);

        $periode = Periode::create([
            'name'        => $request->name,
            'code'        => $request->code,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'description' => $request->description,
            'is_active'   => 1,
        ]);

        $this->createDefaultCriteria($periode->id);

        $criterias = Criteria::where('periode_id', $periode->id)
            ->with('parameters')
            ->get();

        foreach ($request->selected_suppliers as $supplierId) {

            $supplier = Supplier::find($supplierId);
            if (!$supplier) continue;

            $data = $request->supplier_data[$supplierId] ?? [];

            $supplier->update([
                'periode_id'       => $periode->id,
                'price_per_kg'     => $data['price_per_kg'] ?? 0,
                'volume_per_month' => $data['volume_per_month'] ?? 0,
                'on_time_percent'  => $data['on_time_percent'] ?? 0,
                'freq_per_month'   => $data['freq_per_month'] ?? 0,
            ]);

            foreach ($criterias as $c) {

                // mapping value
                if (str_contains($c->name, 'Harga')) {
                    $value = $data['price_per_kg'] ?? 0;
                } elseif (str_contains($c->name, 'Volume')) {
                    $value = $data['volume_per_month'] ?? 0;
                } elseif (str_contains($c->name, 'Ketepatan')) {
                    $value = $data['on_time_percent'] ?? 0;
                } elseif (str_contains($c->name, 'Frekuensi')) {
                    $value = $data['freq_per_month'] ?? 0;
                } else {
                    continue;
                }

                // cari parameter sesuai range
                $param = $c->parameters->first(function ($p) use ($value) {

                    $min = $p->min_value ?? -INF;
                    $max = $p->max_value ?? INF;

                    if ($p->operator == 'lte') return $value <= $max;
                    if ($p->operator == 'gte') return $value >= $min;
                    if ($p->operator == 'equal') return $value == $min;

                    return $value >= $min && $value <= $max;
                });

                SupplierScore::updateOrCreate(
                    [
                        'supplier_id' => $supplier->id,
                        'criteria_id' => $c->id,
                    ],
                    [
                        'parameter_id' => $param?->id,
                        'raw_value'    => $value,
                        'score'        => $param?->score ?? 1,
                    ]
                );
            }
        }

        DB::commit();

        return redirect()->route('periode.index')
            ->with('success', 'Periode berhasil dibuat & supplier masuk ke periode ini');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()->withInput()->with('error', $e->getMessage());
    }
}

    public function generateNextMonth()
    {
        $last = Periode::orderBy('end_date', 'desc')->first();

        $nextMonth = $last 
            ? Carbon::parse($last->end_date)->addMonth()
            : Carbon::now()->addMonth();

        return redirect()->route('periode.create')->withInput([
            'name'       => "Evaluasi " . $nextMonth->translatedFormat('F Y'),
            'code'       => "PER" . $nextMonth->format('Ym'),
            'start_date' => $nextMonth->startOfMonth()->format('Y-m-d'),
            'end_date'   => $nextMonth->endOfMonth()->format('Y-m-d'),
        ]);
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

    $criteriaIds = Criteria::where('periode_id', $periode->id)->pluck('id');

    SupplierScore::whereIn('criteria_id', $criteriaIds)->delete();

    $periode->delete();

    return redirect()->route('periode.index')
        ->with('success', 'Periode berhasil dihapus!');
    }

    private function createDefaultCriteria($periodeId)
    {
        $default = [
            [
                'kode'=>'C1','name'=>'Harga (Rp/kg)','weight'=>0.3,'percentage'=>30,'type'=>'Cost','slug'=>'harga',
                'params'=>[
                    ['score'=>5,'operator'=>'lte','min'=>null,'max'=>180,'desc'=>'≤180'],
                    ['score'=>4,'operator'=>'between','min'=>181,'max'=>184,'desc'=>'181–184'],
                    ['score'=>3,'operator'=>'between','min'=>185,'max'=>189,'desc'=>'185–189'],
                    ['score'=>2,'operator'=>'between','min'=>190,'max'=>194,'desc'=>'190–194'],
                    ['score'=>1,'operator'=>'gte','min'=>195,'max'=>null,'desc'=>'≥195'],
                ]
            ],
            [
                'kode'=>'C2','name'=>'Volume Pasokan (kg/bulan)','weight'=>0.25,'percentage'=>25,'type'=>'Benefit','slug'=>'volume',
                'params'=>[
                    ['score'=>5,'operator'=>'gte','min'=>15000,'max'=>null,'desc'=>'≥15000'],
                    ['score'=>4,'operator'=>'between','min'=>10000,'max'=>14999,'desc'=>'10000–14999'],
                    ['score'=>3,'operator'=>'between','min'=>7000,'max'=>9999,'desc'=>'7000–9999'],
                    ['score'=>2,'operator'=>'between','min'=>4000,'max'=>6999,'desc'=>'4000–6999'],
                    ['score'=>1,'operator'=>'lte','min'=>null,'max'=>3999,'desc'=>'<4000'],
                ]
            ],
            [
                'kode'=>'C3','name'=>'Ketepatan Waktu (%)','weight'=>0.25,'percentage'=>25,'type'=>'Benefit','slug'=>'ketepatan',
                'params'=>[
                    ['score'=>5,'operator'=>'gte','min'=>100,'max'=>null,'desc'=>'100%'],
                    ['score'=>4,'operator'=>'between','min'=>90,'max'=>99,'desc'=>'90–99%'],
                    ['score'=>3,'operator'=>'between','min'=>75,'max'=>89,'desc'=>'75–89%'],
                    ['score'=>2,'operator'=>'between','min'=>50,'max'=>74,'desc'=>'50–74%'],
                    ['score'=>1,'operator'=>'lte','min'=>null,'max'=>49,'desc'=>'<50%'],
                ]
            ],
            [
                'kode'=>'C4','name'=>'Frekuensi Pengiriman (kali/bulan)','weight'=>0.2,'percentage'=>20,'type'=>'Benefit','slug'=>'frekuensi',
                'params'=>[
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
                'periode_id'=>$periodeId,
                'code'=>$c['kode'],
                'kode'=>$c['kode'],
                'name'=>$c['name'],
                'weight'=>$c['weight'],
                'percentage'=>$c['percentage'],
                'type'=>$c['type'],
                'slug'=>$c['slug'],
            ]);

            foreach ($c['params'] as $p) {
                Parameter::create([
                    'criteria_id'=>$criteria->id,
                    'score'=>$p['score'],
                    'operator'=>$p['operator'],
                    'min_value'=>$p['min'],
                    'max_value'=>$p['max'],
                    'description'=>$p['desc'],
                ]);
            }
        }
    }
}