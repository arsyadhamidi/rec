<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKegiatanHarian extends Model
{
    use HasFactory;

    protected $table = "log_kegiatan_harian";
    protected $guarded = [];
    public $timestamps = false;
}
