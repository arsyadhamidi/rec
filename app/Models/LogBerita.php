<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBerita extends Model
{
    use HasFactory;

    protected $table = "log_berita";
    protected $guarded = [];
    public $timestamps = false;
}
