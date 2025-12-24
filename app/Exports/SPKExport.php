<?php

namespace App\Exports;

use App\Models\Criteria;
use App\Models\Periode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Controllers\SPKController;

class SPKExport implements FromCollection, WithHeadings, WithMapping
{
    protected $periodeId;
    protected $results;

    public function __construct($periodeId)
    {
        $this->periodeId = $periodeId;

        $controller = new SPKController();
        $this->results = $controller->calculateSMART($periodeId);
    }

    public function collection()
    {
        return collect($this->results);
    }

    public function headings(): array
    {
        $criteriaList = Criteria::where('periode_id', $this->periodeId)->orderBy('id')->get();

        $heads = ['Rank', 'Kode', 'Nama Supplier', 'Harga/kg', 'Volume/bln', 'Ketepatan', 'Frekuensi'];
        foreach ($criteriaList as $c) {
            $heads[] = $c->code;
        }
        $heads[] = 'Skor Akhir';

        return $heads;
    }

    public function map($row): array
    {
        static $rank = 0;
        $rank++; 

        $criteriaList = Criteria::where('periode_id', $this->periodeId)->orderBy('id')->get();

        $data = [
            $rank,
            $row['supplier']->code,
            $row['supplier']->name,
            $row['supplier']->price_per_kg,
            $row['supplier']->volume_per_month,
            $row['supplier']->on_time_percent,
            $row['supplier']->freq_per_month,
        ];

        foreach ($criteriaList as $c) {
            $data[] = $row['detail'][$c->code]['score'] ?? 0;
        }

        $data[] = $row['score'];

        return $data;
    }
}
