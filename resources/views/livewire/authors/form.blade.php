<?php

use App\Models\Autor;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.livewire')] #[Title('Gestión de Autor')] class extends Component
{
    public ?Autor $author = null;

    // We can use a Form Object or model properties.
    // To minimize redundancy as requested:
    public string $nombre = '';
    public string $apellido = '';
    public string $fecha_nacimiento = '';

    public function mount(?Autor $author)
    {
        if ($author && $author->exists) {
            $this->author = $author;
            $this->fill($author->toArray());
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        if ($this->author && $this->author->exists) {
            $this->author->update($validated);
            $message = 'Autor actualizado con éxito.';
        } else {
            Autor::create($validated);
            $message = 'Autor creado con éxito.';
        }

        session()->flash('success', $message);

        return redirect()->route('authors.index');
    }
}; ?>

<div>
    <flux:container max-width="xl">
        <div class="mb-6">
            <flux:button variant="ghost" icon="arrow-left" class="-ml-2 mb-2" :href="route('authors.index')" wire:navigate>Volver</flux:button>
            <flux:heading size="xl" level="1">{{ $author ? 'Editar Autor' : 'Crear Nuevo Autor' }}</flux:heading>
            <flux:subheading>Modo Livewire Reactivo (Volt)</flux:subheading>
        </div>

        <flux:card>
            <form wire:submit="save" class="space-y-6">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="nombre" placeholder="Ej: Gabriel" />
                    <flux:error name="nombre" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input wire:model="apellido" placeholder="Ej: García Márquez" />
                    <flux:error name="apellido" />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de Nacimiento</flux:label>
                    <flux:input type="date" wire:model="fecha_nacimiento" />
                    <flux:error name="fecha_nacimiento" />
                </flux:field>

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('authors.index')" wire:navigate>Cancelar</flux:button>
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
