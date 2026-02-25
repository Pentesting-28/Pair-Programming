<?php

use App\Models\Book;
use App\Models\Author;
use App\Models\Country;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Services\BookService;
use App\Services\AuthorService;
use App\Services\FileService;
use App\DTOs\BookData;
use App\DTOs\AuthorData;
use App\Livewire\Forms\BookForm;
use App\Livewire\Forms\AuthorForm;
use Livewire\WithFileUploads;
use Flux\Flux;

new #[Layout('layouts.livewire')] #[Title('Gestión de Libro')] class extends Component
{
    use WithFileUploads;

    public BookForm $form;
    public AuthorForm $authorForm;
    public string $quickAuthorSuccess = '';
    public string $quickAuthorError = '';

    public function mount(?Book $book)
    {
        $this->form->setBook($book);
    }

    public function save(BookService $bookService)
    {
        $this->form->validate();

        // Transformación al "Contrato Universal" (DTO)
        $dto = BookData::fromArray($this->form->all());

        if ($this->form->book && $this->form->book->exists) {
            $bookService->update($this->form->book, $dto);
            $message = 'Libro actualizado con éxito.';
        } else {
            $bookService->store($dto);
            $message = 'Libro creado con éxito.';
        }

        session()->flash('success', $message);

        return redirect()->route('livewire.books.index');
    }

    public function saveQuickAuthor(AuthorService $authorService, FileService $fileService)
    {
        $this->quickAuthorError = '';
        $this->quickAuthorSuccess = '';

        try {
            $this->authorForm->validate();

            $photoPath = null;
            if ($this->authorForm->photo_path) {
                $photoPath = $fileService->upload($this->authorForm->photo_path, 'authors');
            }

            $dto = AuthorData::fromArray($this->authorForm->all(), $photoPath);
            $author = $authorService->store($dto);

            // Actualizar el estado del formulario de libros
            $this->form->author_id = $author->id;
            
            // Limpiar el formulario de la modal (servidor)
            $this->authorForm->resetFields();

            // Cerrar modal y notificar éxito
            Flux::modal('create-author-modal')->close();
            $this->quickAuthorSuccess = 'Autor creado con éxito.';
            $this->dispatch('author-created', id: $author->id, name: $author->name . ' ' . $author->last_name);
        } catch (\Exception $e) {
            $this->quickAuthorError = 'Error al crear autor: ' . $e->getMessage();
        }
    }

    public function resetQuickAuthorForm()
    {
        $this->authorForm->resetFields();
        $this->quickAuthorSuccess = '';
        $this->quickAuthorError = '';
    }

    public function with()
    {
        return [
            'authors' => Author::select('id', 'name', 'last_name')
                ->orderBy('name')
                ->get()
                ->map(fn($a) => [
                    'id' => $a->id,
                    'full_name' => $a->name . ' ' . $a->last_name,
                ]),
            'countries' => Country::orderBy('common_name')->get()->map(fn($c) => [
                'id' => $c->id,
                'common_name' => $c->common_name,
                'flagUrl' => $c->flag_url,
            ]),
        ];
    }
}; ?>

<div>
    <flux:container max-width="lg">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <flux:button variant="subtle" size="sm" icon="arrow-left" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200" :href="route('livewire.books.index')" wire:navigate>
                    Volver
                </flux:button>
            </div>
            <div class="flex flex-col gap-1">
                <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">
                    {{ $form->book ? 'Editar Libro' : 'Crear Nuevo Libro' }}
                </flux:heading>
                <flux:subheading class="text-zinc-500 dark:text-zinc-400">
                    {{ $form->book ? 'Actualiza la información del ejemplar: ' . $form->title : 'Registra un nuevo ejemplar en la colección de la biblioteca.' }}
                </flux:subheading>
            </div>
        </div>

        @if($quickAuthorSuccess)
            <flux:callout variant="success" class="mb-6 shadow-sm border-emerald-200/50 dark:border-emerald-900/50">
                {{ $quickAuthorSuccess }}
            </flux:callout>
        @endif

        <flux:card class="p-0 overflow-hidden shadow-sm">
            <form wire:submit="save" class="flex flex-col">
                <div class="p-6 space-y-6">
                    {{-- Autor --}}
                    <flux:field>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <flux:label class="!mb-0">Autor</flux:label>
                            
                            <flux:modal.trigger name="create-author-modal">
                                <flux:button variant="subtle" size="xs" icon="plus" wire:click="resetQuickAuthorForm" class="text-blue-600 hover:text-blue-700 bg-blue-50/50 hover:bg-blue-50 border-blue-100 hover:border-blue-200">
                                    Crear Autor
                                </flux:button>
                            </flux:modal.trigger>
                        </div>

                        <div wire:ignore 
                             wire:key="author-select-container-{{ $form->book?->id ?? 'new' }}"
                             x-data="{ 
                                authors: @js($authors),
                                selected: @entangle('form.author_id')
                             }"
                             x-init="
                                const ts = new TomSelect($refs.select, {
                                    valueField: 'id',
                                    labelField: 'full_name',
                                    searchField: 'full_name',
                                    options: authors,
                                    items: selected ? [selected] : [],
                                    placeholder: 'Selecciona un autor...',
                                    maxOptions: null,
                                    onChange: (value) => { selected = value }
                                });

                                $watch('selected', (value) => {
                                    if (value !== ts.getValue()) {
                                        ts.setValue(value, true);
                                    }
                                });

                                $wire.on('author-created', (data) => {
                                    ts.addOption({ id: data.id, full_name: data.name });
                                    ts.setValue(data.id);
                                });
                             "
                        >
                            <select x-ref="select" required class="w-full">
                                <option value=""></option>
                            </select>
                        </div>
                        <flux:error name="form.author_id" />
                    </flux:field>

                    {{-- Título --}}
                    <flux:field>
                        <flux:label>Título del Libro</flux:label>
                        <flux:input wire:model="form.title" placeholder="Ej: Cien años de soledad" required />
                        <flux:error name="form.title" />
                    </flux:field>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- ISBN --}}
                        <flux:field>
                            <flux:label>ISBN</flux:label>
                            <flux:input wire:model="form.isbn" placeholder="9780000000000" maxlength="13" required />
                            <flux:error name="form.isbn" />
                        </flux:field>

                        {{-- Número de Páginas --}}
                        <flux:field>
                            <flux:label>Número de Páginas</flux:label>
                            <flux:input type="number" wire:model="form.num_pages" placeholder="Ej. 432" min="1" required />
                            <flux:error name="form.num_pages" />
                        </flux:field>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/20">
                    <flux:button variant="ghost" :href="route('livewire.books.index')" wire:navigate class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="shadow-sm">
                        <span wire:loading.remove wire:target="save">
                            {{ $form->book ? 'Actualizar Libro' : 'Guardar Libro' }}
                        </span>
                        <span wire:loading wire:target="save">
                            Guardando...
                        </span>
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </flux:container>

    {{-- Modal para Crear Autor Rápido --}}
    <flux:modal name="create-author-modal" focusable class="max-w-2xl">
        <form wire:submit="saveQuickAuthor" class="space-y-6">
            <div>
                <flux:heading size="lg">Nuevo Autor</flux:heading>
                <flux:subheading>Crea un autor rápidamente para asociarlo al libro sin perder tu progreso.</flux:subheading>

                @if($quickAuthorError)
                    <flux:callout variant="danger" class="mt-4 shadow-sm border-red-200/50 dark:border-red-900/50">
                        {{ $quickAuthorError }}
                    </flux:callout>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="authorForm.name" required placeholder="Ej. Gabriel" />
                    <flux:error name="authorForm.name" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input wire:model="authorForm.last_name" required placeholder="Ej. García Márquez" />
                    <flux:error name="authorForm.last_name" />
                </flux:field>

                <flux:field>
                    <flux:label>Nacimiento</flux:label>
                    <flux:input type="date" wire:model="authorForm.birth_date" />
                    <flux:error name="authorForm.birth_date" />
                </flux:field>

                <flux:field>
                    <flux:label>País</flux:label>
                    <div wire:ignore 
                         wire:key="modal-country-select-container"
                         x-data="{ 
                            countries: @js($countries),
                            selected: @entangle('authorForm.country_id')
                         }"
                         x-init="
                            const ts = new TomSelect($refs.select, {
                                valueField: 'id',
                                labelField: 'common_name',
                                searchField: 'common_name',
                                options: countries,
                                items: selected ? [selected] : [],
                                placeholder: 'Selecciona país...',
                                allowEmptyOption: true,
                                maxOptions: null,
                                render: {
                                    option: (data, escape) => `<div class='flex items-center gap-2'><img src='${escape(data.flagUrl)}' class='h-3 w-5 rounded-sm object-cover' /><span class='text-sm'>${escape(data.common_name)}</span></div>`,
                                    item: (data, escape) => `<div class='flex items-center gap-2'><img src='${escape(data.flagUrl)}' class='h-3 w-5 rounded-sm object-cover' /><span>${escape(data.common_name)}</span></div>`
                                },
                                onChange: (value) => { selected = value }
                            });

                            $watch('selected', (value) => {
                                if (value !== ts.getValue()) {
                                    ts.setValue(value, true);
                                }
                            });
                         "
                    >
                        <select x-ref="select" required class="w-full">
                            <option value=""></option>
                        </select>
                    </div>
                    <flux:error name="authorForm.country_id" />
                </flux:field>
            </div>

            <div class="w-full h-px bg-zinc-200/60 dark:bg-zinc-700/50 my-2"></div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-start" 
                 x-data="{ localPreview: null }"
                 x-on:author-created.window="localPreview = null"
            >
                <div class="sm:col-span-3">
                    <flux:label class="mb-3">Previsualización</flux:label>
                    <div class="relative group author-image-preview-wrapper mx-auto sm:mx-0">
                        <div class="w-full h-full rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 flex items-center justify-center overflow-hidden transition-all group-hover:border-blue-400">
                            <template x-if="localPreview">
                                <img :src="localPreview" class="w-full h-full object-cover" />
                            </template>
                            <div x-show="!localPreview" class="flex flex-col items-center justify-center text-zinc-400">
                                <flux:icon name="camera" class="w-6 h-6 mb-1 opacity-50" variant="outline" />
                                <span class="text-[8px] uppercase font-bold tracking-wider">Subir</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-9">
                    <flux:field>
                        <flux:label>Fotografía del Autor</flux:label>
                        <flux:input type="file" wire:model="authorForm.photo_path" accept="image/*" 
                                    @change="if($event.target.files[0]) localPreview = URL.createObjectURL($event.target.files[0])" />
                        <flux:description class="mt-2 text-xs">Formatos: JPG, PNG, WebP. Resolución recomendada: 400x400px (Máx 2MB).</flux:description>
                        <flux:error name="authorForm.photo_path" />
                    </flux:field>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-200/60 dark:border-zinc-700/50">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" class="px-6">
                    <span wire:loading.remove wire:target="saveQuickAuthor">Guardar Autor</span>
                    <span wire:loading wire:target="saveQuickAuthor">Guardando...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
