<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSpesialis extends Model
{
    use HasFactory;

    protected $table = "log_spesialis";
    protected $guarded = [];
    public $timestamps = false;
}
