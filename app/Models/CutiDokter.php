<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiDokter extends Model
{
    use HasFactory;

    protected $table = "cuti_dokter";
    protected $guarded = [];
    public $timestamps = false;
}
