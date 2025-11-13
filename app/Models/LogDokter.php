<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogDokter extends Model
{
    use HasFactory;

    protected $table = "log_dokter";
    protected $guarded = [];
    public $timestamps = false;
}
