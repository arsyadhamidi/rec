<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinKeluar extends Model
{
    use HasFactory;

    protected $table = "izin_keluar";
    protected $guarded = [];
    public $timestamps = false;
}
