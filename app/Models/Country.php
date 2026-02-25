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

    /**
     * Get the country's flag URL.
     */
    protected function flagUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function () {
                if (!$this->flag_svg_path) {
                    return asset('assets_panel/image_placeholder.jpg');
                }

                return asset($this->flag_svg_path);
            },
        );
    }
}
