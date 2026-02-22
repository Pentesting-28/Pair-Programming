<?php

namespace App\DTOs;

readonly class CountryData
{
    public function __construct(
        public string $commonName,
        public string $officialName,
        public string $cca3,
        public string $pngUrl,
        public string $svgUrl,
    ) {}

    /**
     * Create a DTO from an API response array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            commonName: $data['name']['common'],
            officialName: $data['name']['official'],
            cca3: $data['cca3'] ?? 'N/A',
            pngUrl: $data['flags']['png'],
            svgUrl: $data['flags']['svg'],
        );
    }
}
