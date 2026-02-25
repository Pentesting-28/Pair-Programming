<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'isbn' => 'unique:books,isbn|required|string|max:20',
            'num_pages' => 'required|integer|min:1',
            'author_id' => 'required|exists:authors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.min' => 'El título debe tener al menos 5 caracteres.',
            'title.max' => 'El título no puede exceder los 255 caracteres.',
            'isbn.required' => 'El ISBN es obligatorio.',
            'isbn.unique' => 'El ISBN ya existe.',
            'isbn.string' => 'El ISBN debe ser una cadena de texto.',
            'isbn.max' => 'El ISBN no puede exceder los 20 caracteres.',
            'num_pages.required' => 'El número de páginas es obligatorio.',
            'num_pages.integer' => 'El número de páginas debe ser un número entero.',
            'num_pages.min' => 'El número de páginas debe ser al menos 1.',
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'isbn' => 'ISBN',
            'num_pages' => 'número de páginas',
            'author_id' => 'autor',
        ];
    }
}
