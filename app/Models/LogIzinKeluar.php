<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogIzinKeluar extends Model
{
    use HasFactory;

    protected $table = "log_izin_keluar";
    protected $guarded = [];
    public $timestamps = false;
}
