<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpesialisExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Map data to include country name
        return $this->data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_spesialis' => $item->nama_spesialis,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Spesialis',
        ];
    }
}
