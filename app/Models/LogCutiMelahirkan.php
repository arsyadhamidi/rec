<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCutiMelahirkan extends Model
{
    use HasFactory;

    protected $table = "log_cuti_melahirkan";
    protected $guarded = [];
    public $timestamps = false;
}
