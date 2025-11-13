<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IzinKeluarExport implements FromCollection, WithHeadings
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
                'tgl_izin' => $item->tgl_izin,
                'jam_keluar' => $item->jam_keluar,
                'jam_kembali' => $item->jam_kembali,
                'keperluan' => $item->keperluan,
                'nama_atasan' => $item->nama_atasan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Tanggal Izin',
            'Jam Keluar',
            'Jam Kembali',
            'Keperluan',
            'Nama Atasan',
        ];
    }
}
