<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Sube un archivo a un disco y carpeta específicos.
     * centralizando aquí toda la lógica de almacenamiento.
     */
    public function upload(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        // En el futuro, si cambias a S3, solo cambias el disco aquí o en el .env
        return $file->store($folder, $disk);
    }

    /**
     * Elimina un archivo si existe.
     */
    public function delete(?string $path, string $disk = 'public'): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}
