<?php

use App\Models\Author;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.livewire')] #[Title('Gestión de Autor')] class extends Component
{
    public ?Author $author = null;

    public string $name = '';
    public string $last_name = '';
    public string $birth_date = '';

    public function mount(?Author $author)
    {
        if ($author && $author->exists) {
            $this->author = $author;
            $this->name = $author->name;
            $this->last_name = $author->last_name;
            $this->birth_date = $author->birth_date ?? '';
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
        ]);

        if ($this->author && $this->author->exists) {
            $this->author->update($validated);
            $message = 'Autor actualizado con éxito.';
        } else {
            Author::create($validated);
            $message = 'Autor creado con éxito.';
        }

        session()->flash('success', $message);

        return redirect()->route('livewire.authors.index');
    }
}; ?>

<div>
    <flux:container max-width="xl">
        <div class="mb-6">
            <flux:button variant="ghost" icon="arrow-left" class="-ml-2 mb-2" :href="route('livewire.authors.index')" wire:navigate>Volver</flux:button>
            <flux:heading size="xl" level="1">{{ $author ? 'Editar Autor' : 'Crear Nuevo Autor' }}</flux:heading>
            <flux:subheading>Modo Livewire Reactivo (Volt)</flux:subheading>
        </div>

        <flux:card>
            <form wire:submit="save" class="space-y-6">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="name" placeholder="Ej: Gabriel" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input wire:model="last_name" placeholder="Ej: García Márquez" />
                    <flux:error name="last_name" />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de Nacimiento</flux:label>
                    <flux:input type="date" wire:model="birth_date" />
                    <flux:error name="birth_date" />
                </flux:field>

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('livewire.authors.index')" wire:navigate>Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">
                        <span wire:loading.remove wire:target="save">
                            {{ $author ? 'Actualizar Autor' : 'Guardar Autor' }}
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
