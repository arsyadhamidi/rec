<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogLevel extends Model
{
    use HasFactory;

    protected $table = "log_level";
    protected $guarded = [];
    public $timestamps = false;
}
