<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
