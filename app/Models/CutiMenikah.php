<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiMenikah extends Model
{
    use HasFactory;

    protected $table = "cuti_menikah";
    protected $guarded = [];
    public $timestamps = false;
}
