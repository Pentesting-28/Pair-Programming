<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        "title",
        "isbn",
        "num_pages",
        "autor_id"
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }
}
