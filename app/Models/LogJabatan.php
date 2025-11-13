<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogJabatan extends Model
{
    use HasFactory;

    protected $table = "log_jabatan";
    protected $guarded = [];
    public $timestamps = false;
}
