<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Livewire\Form;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;

class BookForm extends Form
{
    public ?Book $book = null;

    public string $title = '';
    public string $isbn = '';
    public $num_pages = '';
    public $author_id = '';

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => [
                'required',
                'string',
                'size:13',
                Rule::unique('books')->ignore($this->book?->id)
            ],
            'num_pages' => 'required|integer|min:1',
            'author_id' => 'required|exists:authors,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'isbn.required' => 'El ISBN es obligatorio.',
            'isbn.size' => 'El ISBN debe tener exactamente 13 caracteres.',
            'isbn.unique' => 'Este ISBN ya está registrado.',
            'num_pages.required' => 'El número de páginas es obligatorio.',
            'num_pages.integer' => 'Debe ser un número entero.',
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.exists' => 'El autor seleccionado no es válido.',
        ];
    }

    public function setBook(?Book $book)
    {
        if ($book && $book->exists) {
            $this->book = $book;
            $this->title = $book->title;
            $this->isbn = $book->isbn;
            $this->num_pages = $book->num_pages;
            $this->author_id = $book->author_id;
        }
    }

    public function resetFields()
    {
        $this->reset(['title', 'isbn', 'num_pages', 'author_id', 'book']);
    }
}
