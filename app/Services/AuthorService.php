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

        if ($request->hasFile('photo_path')) {
            $photoPath = $this->fileService->upload(
                $request->file('photo_path'), 
                'authors'
            );
        }

        $dto = AuthorData::fromArray($request->validated(), $photoPath);

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
            $this->fileService->delete($author->photo_path);
            $photoPath = $this->fileService->upload($newPhoto, 'authors');
        }

        $dto = AuthorData::fromArray($validatedData, $photoPath);

        return $author->update($dto->toArray());
    }

    public function delete(Author $author): bool
    {
        if ($author->photo_path) {
            $this->fileService->delete($author->photo_path);
        }
        return $author->delete();
    }
}
