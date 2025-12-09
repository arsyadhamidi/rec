<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKerjasama extends Model
{
    use HasFactory;

    protected $table = "log_kerjasama";
    protected $guarded = [];
    public $timestamps = false;
}
