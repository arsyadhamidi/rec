<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPulangCepat extends Model
{
    use HasFactory;

    protected $table = "log_pulang_cepat";
    protected $guarded = [];
    public $timestamps = false;
}
