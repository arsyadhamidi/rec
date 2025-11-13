<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSakit extends Model
{
    use HasFactory;

    protected $table = "log_sakit";
    protected $guarded = [];
    public $timestamps = false;
}
