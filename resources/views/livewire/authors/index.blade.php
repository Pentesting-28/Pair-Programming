<?php

use App\Models\Autor;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.livewire')] #[Title('Administración de Autores')] class extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Autor $author)
    {
        $author->delete();
        session()->flash('success', 'Autor eliminado con éxito.');
    }

    public function with()
    {
        return [
            'authors' => Autor::where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('apellido', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <flux:container>
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl" level="1">Administración de Autores</flux:heading>
                <flux:subheading>Modo Livewire Reactivo (Volt)</flux:subheading>
            </div>

            <flux:button variant="primary" :href="route('authors.new')" icon="plus" wire:navigate>
                Nuevo Autor
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" class="mb-6">
                {{ session('success') }}
            </flux:callout>
        @endif

        <div class="mb-6">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar autor por nombre o apellido..." 
                icon="magnifying-glass" 
            />
        </div>

        <flux:card class="overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nombre</flux:table.column>
                    <flux:table.column>Apellido</flux:table.column>
                    <flux:table.column>Fecha Nac.</flux:table.column>
                    <flux:table.column align="end">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($authors as $author)
                        <flux:table.row :key="$author->id">
                            <flux:table.cell class="font-medium">{{ $author->nombre }}</flux:table.cell>
                            <flux:table.cell>{{ $author->apellido }}</flux:table.cell>
                            <flux:table.cell>{{ $author->fecha_nacimiento ? \Carbon\Carbon::parse($author->fecha_nacimiento)->format('d/m/Y') : '-' }}</flux:table.cell>
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" size="sm" icon="pencil" :href="route('authors.edit', $author)" wire:navigate />
                                    
                                    <flux:button variant="ghost" size="sm" icon="trash" 
                                        wire:click="delete({{ $author->id }})" 
                                        wire:confirm="¿Estás seguro de que deseas eliminar este autor?" 
                                    />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center py-8 text-zinc-500">
                                No se encontraron autores con el criterio de búsqueda.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $authors->links() }}
            </div>
        </flux:card>
    </flux:container>
</div>
