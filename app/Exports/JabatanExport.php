<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JabatanExport implements FromCollection, WithHeadings
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
                'kd_jabatan' => $item->kd_jabatan,
                'nm_jabatan' => $item->nm_jabatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Jabatan',
            'Nama Jabatan',
        ];
    }
}
