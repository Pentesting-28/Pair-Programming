<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'country_id',
        'birth_date',
        'photo_path',
    ];

    /**
     * Scope for search authors by name or last name.
     */
    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;
        return $query->whereAny(
            ['name', 'last_name'], 
            'like', 
            "%{$term}%"
        );
    }

    /**
     * Get the author's photo URL.
     */
    protected function photoUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function () {
                if (!$this->photo_path) {
                    return asset('assets_panel/image_placeholder.jpg');
                }

                /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
                $storage = \Illuminate\Support\Facades\Storage::disk('public');
                
                return $storage->url($this->photo_path);
            },
        );
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
