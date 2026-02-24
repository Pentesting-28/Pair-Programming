<?php

namespace App\Services;

use App\Models\Book;
use App\DTOs\BookData;
use App\Http\Requests\Book\StoreRequest;

class BookService
{
    /**
     * Maneja la creación integral de un libro.
     * Cumple SRP al coordinar el almacenamiento y la base de datos.
     */
    public function store(StoreRequest $request): Book
    {
        $dto = BookData::fromArray($request->validated());

        return Book::create($dto->toArray());
    }

    /**
     * Maneja la actualización de un libro.
     */
    public function update(Book $book, array $validatedData): bool
    {
        $dto = BookData::fromArray($validatedData);

        return $book->update($dto->toArray());
    }

    public function delete(Book $book): bool
    {
        return $book->delete();
    }
}
