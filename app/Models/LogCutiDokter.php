<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCutiDokter extends Model
{
    use HasFactory;

    protected $table = "log_cuti_dokter";
    protected $guarded = [];
    public $timestamps = false;
}
