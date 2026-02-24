<?php

namespace App\Services;

use App\Models\Author;
use App\DTOs\AuthorData;
use App\Http\Requests\Author\StoreRequest;

class AuthorService
{
    public function __construct(
        protected FileService $fileService
    ) {}

    /**
     * Maneja la creación integral de un autor.
     * Recibe un DTO ya procesado (incluyendo rutas de archivos si existen).
     */
    public function store(AuthorData $data): Author
    {
        return Author::create($data->toArray());
    }

    /**
     * Maneja la actualización de un autor.
     * Recibe el modelo y el DTO con los nuevos datos.
     */
    public function update(Author $author, AuthorData $data): bool
    {
        return $author->update($data->toArray());
    }

    public function delete(Author $author): bool
    {
        // Podemos implemensar SotfDelete mas adelante 
        if ($author->books()->exists()) {
            throw new \Exception("No se puede eliminar el autor porque tiene libros asociados.");
        }

        if ($author->photo_path) {
            $this->fileService->delete($author->photo_path);
        }

        return $author->delete();
    }
}
