<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Reusable Query Component for searching authors by name or last name.
 * 
 * Usage: $query->tap(new AuthorSearch($searchTerm))
 */
class AuthorSearch
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
            $query->whereAny(
                ['name', 'last_name'], 
                'like', 
                "%{$this->term}%"
            );
        });
    }
}
