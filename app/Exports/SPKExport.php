<?php

namespace App\Exports;

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
        return [
            'Rank',
            'Kode',
            'Nama Supplier',
            'Harga/kg',
            'Volume/bln',
            'Ketepatan',
            'Frekuensi',
            'Skor Akhir'
        ];
    }

    public function map($row): array
    {
        static $rank = 0;
        $rank++;

        return [
            $rank,
            $row['supplier']->code,
            $row['supplier']->name,
            $row['supplier']->price_per_kg,
            $row['supplier']->volume_per_month,
            $row['supplier']->on_time_percent,
            $row['supplier']->freq_per_month,
            $row['score']
        ];
    }
}