<?php

namespace App\Services;

use App\Models\Book;
use App\DTOs\BookData;

class BookService
{
    /**
     * Maneja la creación integral de un libro.
     * Cumple SRP al coordinar el almacenamiento y la base de datos.
     */
    public function store(BookData $dto): Book
    {
        return Book::create($dto->toArray());
    }

    /**
     * Maneja la actualización de un libro.
     */
    public function update(Book $book, BookData $dto): bool
    {
        return $book->update($dto->toArray());
    }

    public function delete(Book $book): bool
    {
        return $book->delete();
    }
}
