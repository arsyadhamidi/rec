<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinTerlambat extends Model
{
    use HasFactory;

    protected $table = "izin_terlambat";
    protected $guarded = [];
    public $timestamps = false;
}
