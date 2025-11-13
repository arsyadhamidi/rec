<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiTahunan extends Model
{
    use HasFactory;

    protected $table = "cuti_tahunan";
    protected $guarded = [];
    public $timestamps = false;
}
