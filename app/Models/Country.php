<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_name',
        'official_name',
        'cca3',
        'flag_png_path',
        'flag_svg_path',
    ];
}
