<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DokterExport implements FromCollection, WithHeadings
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
                'slug' => $item->slug,
                'nm_dokter' => $item->nm_dokter,
                'tmp_lahir' => $item->tmp_lahir,
                'tgl_lahir' => $item->tgl_lahir,
                'jk' => $item->jk == '1' ? 'Laki-Laki' : 'Perempuan',
                'alamat' => $item->alamat,
                'telp_dokter' => $item->telp_dokter,
                'tentang' => $item->tentang,
                'pendidikan' => $item->pendidikan,
                'keahlian' => $item->keahlian,
                'nama_spesialis' => $item->nama_spesialis,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Slug',
            'Nama Dokter',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Telepon',
            'Tentang',
            'Pendidikan',
            'Keahlian',
            'Spesialis',
        ];
    }
}
