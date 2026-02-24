<?php

namespace App\Livewire\Forms;

use App\Models\Author;
use Livewire\Form;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

use Illuminate\Validation\Rule;

class AuthorForm extends Form
{
    use WithFileUploads;

    public ?Author $author = null;

    public string $name = '';
    public string $last_name = '';
    public string $birth_date = '';
    public $country_id = '';
    public $photo_path = null;
    public bool $remove_photo = false;

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('authors')
                    ->where(fn ($query) => $query->where('last_name', $this->last_name))
                    ->ignore($this->author?->id)
            ],
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'country_id' => 'required|exists:countries,id',
            'photo_path' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe un autor con este nombre y apellido.',
            'last_name.required' => 'El apellido es obligatorio.',
            'birth_date.date' => 'La fecha debe ser válida.',
            'country_id.required' => 'El país es obligatorio.',
            'country_id.exists' => 'El país seleccionado no es válido.',
            'photo_path.image' => 'El archivo debe ser una imagen.',
            'photo_path.max' => 'La imagen no debe superar los 2MB.',
        ];
    }

    public function setAuthor(?Author $author)
    {
        if ($author && $author->exists) {
            $this->author = $author;
            $this->name = $author->name;
            $this->last_name = $author->last_name;
            $this->birth_date = $author->birth_date ?? '';
            $this->country_id = $author->country_id;
        }
    }

    public function resetFields()
    {
        $this->reset(['name', 'last_name', 'birth_date', 'country_id', 'photo_path', 'author', 'remove_photo']);
    }
}
