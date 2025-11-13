<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PotongGajiExport implements FromCollection, WithHeadings
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
                'tgl_masuk' => $item->tgl_masuk,
                'lama_cuti' => $item->lama_cuti,
                'tahun' => $item->tahun,
                'nama_pj' => $item->nama_pj,
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
            'Tanggal Masuk',
            'Lama Cuti',
            'Tahun',
            'Nama Penanggung Jawab',
            'Nama Atasan',
        ];
    }
}
