<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiMelahirkan extends Model
{
    use HasFactory;

    protected $table = "cuti_melahirkan";
    protected $guarded = [];
    public $timestamps = false;
}
