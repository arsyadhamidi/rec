<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCutiTahunan extends Model
{
    use HasFactory;

    protected $table = "log_cuti_tahunan";
    protected $guarded = [];
    public $timestamps = false;
}
