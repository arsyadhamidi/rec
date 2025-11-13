<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPotongGaji extends Model
{
    use HasFactory;

    protected $table = "log_potong_gaji";
    protected $guarded = [];
    public $timestamps = false;
}
