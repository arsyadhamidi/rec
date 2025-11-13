<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCutiMenikah extends Model
{
    use HasFactory;

    protected $table = "log_cuti_menikah";
    protected $guarded = [];
    public $timestamps = false;
}
