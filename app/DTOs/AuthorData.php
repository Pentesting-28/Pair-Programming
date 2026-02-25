<?php

namespace App\DTOs;

readonly class AuthorData
{
    public function __construct(
        public string $name,
        public string $last_name,
        public int $country_id,
        public ?string $birth_date,
        public ?string $photo_path = null
    ) {}

    /**
     * Crea el DTO desde un array de datos ya validados.
     * Cero lógica de archivos aquí.
     */
    public static function fromArray(array $validatedData, ?string $photoPath = null): self
    {
        return new self(
            name: $validatedData['name'],
            last_name: $validatedData['last_name'],
            country_id: (int) $validatedData['country_id'],
            birth_date: (!empty($validatedData['birth_date'])) ? $validatedData['birth_date'] : null,
            photo_path: $photoPath
        );
    }

    /**
     * Convierte el DTO a un array apto para el método create() de Eloquent.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'country_id' => $this->country_id,
            'birth_date' => $this->birth_date,
            'photo_path' => $this->photo_path,
        ];
    }
}