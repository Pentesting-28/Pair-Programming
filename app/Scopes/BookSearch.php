<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Reusable Query Component for searching books.
 * Filters by title, ISBN, or author name/last_name.
 */
class BookSearch
{
    public function __construct(
        private ?string $term
    ) {}

    /**
     * Apply the search logic to the query builder instance.
     */
    public function __invoke(Builder $query): void
    {
        $query->when($this->term, function ($query) {
            $query->where(function ($query) {
                $query->whereAny(['title', 'isbn'], 'like', "%{$this->term}%")
                    ->orWhereHas('author', function ($query) {
                        $query->whereAny(['name', 'last_name'], 'like', "%{$this->term}%");
                    });
            });
        });
    }
}
