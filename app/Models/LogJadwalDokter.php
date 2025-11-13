<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogJadwalDokter extends Model
{
    use HasFactory;

    protected $table = "log_jadwal_dokter";
    protected $guarded = [];
    public $timestamps = false;
}
