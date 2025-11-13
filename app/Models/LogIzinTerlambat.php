<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogIzinTerlambat extends Model
{
    use HasFactory;

    protected $table = "log_izin_terlambat";
    protected $guarded = [];
    public $timestamps = false;
}
