<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LemburExport implements FromCollection, WithHeadings
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
                'nama_karyawan' => $item->nama_karyawan,
                'tgl_mulai' => $item->tgl_mulai,
                'tgl_selesai' => $item->tgl_selesai,
                'jam_mulai' => $item->jam_mulai,
                'jam_selesai' => $item->jam_selesai,
                'alasan' => $item->alasan,
                'total' => $item->total,
                'nama_atasan' => $item->nama_atasan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Jam Mulai',
            'Jam Selesai',
            'Alasan',
            'Total',
            'Nama Atasan',
        ];
    }
}
