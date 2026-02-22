<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'country_id',
        'birth_date',
        'photo_path',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
