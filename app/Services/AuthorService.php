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
     * Cumple SRP al coordinar el almacenamiento y la base de datos.
     */
    public function store(StoreRequest $request): Author
    {
        $photoPath = null;

        // Delegamos el almacenamiento al FileService
        if ($request->hasFile('photo_path')) {
            $photoPath = $this->fileService->upload(
                $request->file('photo_path'), 
                'authors'
            );
        }

        // Creamos el DTO con datos limpios
        $dto = AuthorData::fromArray($request->validated(), $photoPath);

        // Retornamos el modelo creado
        return Author::create($dto->toArray());
    }

    /**
     * Maneja la actualización de un autor.
     * Elimina la foto anterior si se sube una nueva.
     */
    public function update(Author $author, array $validatedData, $newPhoto = null): bool
    {
        $photoPath = $author->photo_path;

        if ($newPhoto) {
            // Eliminamos la foto anterior físicamente
            $this->fileService->delete($author->photo_path);
            
            // Subimos la nueva
            $photoPath = $this->fileService->upload($newPhoto, 'authors');
        }

        $dto = AuthorData::fromArray($validatedData, $photoPath);

        return $author->update($dto->toArray());
    }
}
