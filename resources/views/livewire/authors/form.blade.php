<?php

use App\Models\Author;
use App\Models\Country;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Services\AuthorService;
use App\Services\FileService;
use App\DTOs\AuthorData;
use App\Livewire\Forms\AuthorForm;
use Livewire\WithFileUploads;

new #[Layout('layouts.livewire')] #[Title('Gestión de Autor')] class extends Component
{
    use WithFileUploads;

    public AuthorForm $form;

    public function mount(?Author $author)
    {
        $this->form->setAuthor($author);
    }

    public function save(AuthorService $authorService, FileService $fileService)
    {
        $validatedData = $this->form->validate();

        $photoPath = $this->form->author?->photo_path;

        // Lógica de archivos
        if ($this->form->remove_photo && !$this->form->photo_path) {
            if ($photoPath) $fileService->delete($photoPath);
            $photoPath = null;
        }

        if ($this->form->photo_path) {
            if ($photoPath) $fileService->delete($photoPath);
            $photoPath = $fileService->upload($this->form->photo_path, 'authors');
        }

        // Asegurar que birth_date sea null si está vacío
        $data = $this->form->all();
        $data['birth_date'] = !empty($data['birth_date']) ? $data['birth_date'] : null;

        // Transformación al "Contrato Universal" (DTO)
        $dto = AuthorData::fromArray($data, $photoPath);

        if ($this->form->author && $this->form->author->exists) {
            $authorService->update($this->form->author, $dto);
            $message = 'Autor actualizado con éxito.';
        } else {
            $authorService->store($dto);
            $message = 'Autor creado con éxito.';
        }

        session()->flash('success', $message);

        return redirect()->route('livewire.authors.index');
    }

    public function with()
    {
        return [
            'countries' => Country::orderBy('common_name')->get()->map(fn($c) => [
                'id' => $c->id,
                'common_name' => $c->common_name,
                'flagUrl' => $c->flag_url,
            ]),
        ];
    }
}; ?>

<div>
    <flux:container max-width="xl">
        <div class="mb-6">
            <flux:button variant="ghost" icon="arrow-left" class="-ml-2 mb-2" :href="route('livewire.authors.index')" wire:navigate>Volver</flux:button>
            <flux:heading size="xl" level="1">{{ $form->author ? 'Editar Autor' : 'Crear Nuevo Autor' }}</flux:heading>
            <flux:subheading>Modo Livewire 4 SFC Nativo - {{ $form->author ? $form->name . ' ' . $form->last_name : 'Nuevo registro' }}</flux:subheading>
        </div>

        <flux:card>
            <form wire:submit="save" class="space-y-6">
                {{-- Nombre --}}
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="form.name" required />
                    <flux:error name="form.name" />
                </flux:field>

                {{-- Apellido --}}
                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input wire:model="form.last_name" required />
                    <flux:error name="form.last_name" />
                </flux:field>

                {{-- Fecha de Nacimiento --}}
                <flux:field>
                    <flux:label>Fecha de Nacimiento</flux:label>
                    <flux:input type="date" wire:model="form.birth_date" />
                    <flux:error name="form.birth_date" />
                </flux:field>

                {{-- País --}}
                <flux:field>
                    <flux:label>País</flux:label>
                    <div wire:ignore 
                         wire:key="country-select-container-{{ $form->author?->id ?? 'new' }}"
                         x-data="{ 
                            countries: @js($countries),
                            selected: @entangle('form.country_id')
                         }"
                         x-init="
                            const ts = new TomSelect($refs.select, {
                                valueField: 'id',
                                labelField: 'common_name',
                                searchField: 'common_name',
                                options: countries,
                                items: selected ? [selected] : [],
                                placeholder: 'Selecciona un país...',
                                maxOptions: null,
                                render: {
                                    option: (data, escape) => `<div class='flex items-center gap-3'><img src='${escape(data.flagUrl)}' class='h-4 w-6 rounded-sm object-cover shadow-xs border border-zinc-200/50' /><span>${escape(data.common_name)}</span></div>`,
                                    item: (data, escape) => `<div class='flex items-center gap-2'><img src='${escape(data.flagUrl)}' class='h-4 w-6 rounded-sm object-cover shadow-xs border border-zinc-200/50' /><span>${escape(data.common_name)}</span></div>`
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
                    <flux:error name="form.country_id" />
                </flux:field>

                {{-- Fotografía --}}
                <div class="space-y-4" x-data="{ localPreview: null }">
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <div class="relative group author-image-preview-wrapper">
                            <div class="w-full h-full rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center overflow-hidden transition-all group-hover:border-zinc-300 dark:group-hover:border-zinc-600">
                                {{-- Previsualización instantánea con Alpine (Nueva imagen) --}}
                                <template x-if="localPreview">
                                    <img :src="localPreview" class="w-full h-full object-cover" />
                                </template>
                                
                                {{-- Imagen existente o Placeholder --}}
                                <template x-if="!localPreview">
                                    <div class="w-full h-full relative flex items-center justify-center">
                                        @if ($form->author && $form->author->photo_path)
                                            <img src="{{ $form->author->photo_url }}" 
                                                 class="w-full h-full object-cover" 
                                                 :class="{ 'hidden': $wire.form.remove_photo }" />
                                        @endif
                                        
                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-zinc-500 dark:group-hover:text-zinc-400" 
                                             :class="{ 'hidden': !$wire.form.remove_photo && @js($form->author && $form->author->photo_path) }"
                                             x-cloak>
                                            <flux:icon name="camera" class="w-10 h-10 mb-2 opacity-50" variant="outline" />
                                            <span class="text-[10px] uppercase tracking-wider font-semibold">Subir foto</span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            {{-- Botón para quitar la NUEVA imagen seleccionada --}}
                            <button x-show="localPreview" 
                                    x-cloak
                                    type="button" 
                                    @click="localPreview = null; $wire.set('form.photo_path', null)" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors z-20">
                                <flux:icon name="x-mark" class="w-4 h-4" variant="mini" />
                            </button>

                            {{-- Botón para quitar la imagen EXISTENTE --}}
                            @if ($form->author && $form->author->photo_path)
                                <button x-show="!localPreview && !$wire.form.remove_photo" 
                                        x-cloak
                                        type="button" 
                                        @click="$wire.set('form.remove_photo', true)" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors z-20">
                                    <flux:icon name="x-mark" class="w-4 h-4" variant="mini" />
                                </button>
                                
                                {{-- Botón para DESHACER la eliminación de la imagen existente --}}
                                <button x-show="!localPreview && $wire.form.remove_photo" 
                                        x-cloak
                                        type="button" 
                                        @click="$wire.set('form.remove_photo', false)" 
                                        class="absolute -top-2 -right-2 bg-zinc-500 text-white rounded-full p-1 shadow-lg hover:bg-zinc-600 transition-colors z-20">
                                    <flux:icon name="arrow-uturn-left" class="w-4 h-4" variant="mini" />
                                </button>
                            @endif
                        </div>
                        
                        <flux:field class="flex-1 w-full">
                            <flux:label>Fotografía</flux:label>
                            <flux:input type="file" 
                                        wire:model="form.photo_path" 
                                        accept="image/*" 
                                        @change="if($event.target.files[0]) { localPreview = URL.createObjectURL($event.target.files[0]); $wire.set('form.remove_photo', false) }" 
                            />
                            <flux:description class="mt-1 text-xs">Deja este campo vacío para mantener la foto actual. Formatos: JPG, PNG, WebP.</flux:description>
                            <flux:error name="form.photo_path" />
                        </flux:field>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('livewire.authors.index')" wire:navigate>Cancelar</flux:button>
                    <flux:button type="submit" variant="primary" class="shadow-sm">
                        <span wire:loading.remove wire:target="save">
                            {{ $form->author ? 'Actualizar Autor' : 'Guardar Autor' }}
                        </span>
                        <span wire:loading wire:target="save">
                            Guardando...
                        </span>
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </flux:container>
</div>
