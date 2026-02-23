<?php

namespace App\DTOs;

readonly class BookData
{
    public function __construct(
        public string $title,
        public string $isbn,
        public int $num_pages,
        public int $author_id
    ) {}

    /**
     * Crea el DTO desde un array de datos ya validados.
     * Cero lógica de archivos aquí.
     */
    public static function fromArray(array $validatedData): self
    {
        return new self(
            title: $validatedData['title'],
            isbn: $validatedData['isbn'],
            num_pages: (int) $validatedData['num_pages'],
            author_id: (int) $validatedData['author_id']
        );
    }

    /**
     * Convierte el DTO a un array apto para el método create() de Eloquent.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'isbn' => $this->isbn,
            'num_pages' => $this->num_pages,
            'author_id' => $this->author_id,
        ];
    }
}