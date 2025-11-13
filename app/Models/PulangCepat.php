<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PulangCepat extends Model
{
    use HasFactory;

    protected $table = "pulang_cepat";
    protected $guarded = [];
    public $timestamps = false;
}
