<?php

use App\Models\Author;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Services\AuthorService;

new #[Layout('layouts.livewire')] #[Title('Administración de Autores')] class extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Author $author, AuthorService $authorService)
    {
        try {
            $authorService->delete($author);
            session()->flash('success', 'Autor eliminado con éxito.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function with()
    {
        return [
            'authors' => Author::select('id', 'name', 'last_name', 'birth_date', 'country_id', 'photo_path')
                ->with('country:id,common_name,flag_svg_path')
                ->tap(new \App\Scopes\AuthorSearch($this->search))
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <flux:container>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div class="flex flex-col gap-1">
                <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">Administración de Autores</flux:heading>
                <flux:subheading class="text-zinc-500 dark:text-zinc-400">Modo Livewire Reactivo (Volt)</flux:subheading>
            </div>

            <flux:button variant="primary" :href="route('livewire.authors.new')" icon="plus" class="shadow-sm" wire:navigate>
                Nuevo Autor
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" class="mb-6 shadow-sm border-emerald-200/50 dark:border-emerald-900/50">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if(session('error'))
            <flux:callout variant="danger" class="mb-6 shadow-sm border-red-200/50 dark:border-red-900/50">
                {{ session('error') }}
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
                    <flux:table.column>Id</flux:table.column>
                    <flux:table.column>Autor</flux:table.column>
                    <flux:table.column>Fecha Nac.</flux:table.column>
                    <flux:table.column>País</flux:table.column>
                    <flux:table.column align="end">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($authors as $author)
                        <flux:table.row :key="$author->id" class="group hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors duration-200">
                            {{-- Columna ID --}}
                            <flux:table.cell class="font-medium text-zinc-600 dark:text-zinc-400">
                                {{ $author->id }}
                            </flux:table.cell>

                            {{-- Columna Autor --}}
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $author->photo_url }}" alt="Avatar de {{ $author->name }}" class="h-9 w-9 shrink-0 rounded-full object-cover border border-zinc-200/80 dark:border-zinc-700/80 shadow-sm" />
                                    <div class="flex flex-col">
                                        <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $author->name }} {{ $author->last_name }}
                                        </span>
                                    </div>
                                </div>
                            </flux:table.cell>

                            {{-- Columna Fecha --}}
                            <flux:table.cell class="text-zinc-600 dark:text-zinc-400">
                                {{ $author->birth_date ?? '—' }}
                            </flux:table.cell>

                            {{-- Columna País --}}
                            <flux:table.cell>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 border border-zinc-200/80 dark:border-zinc-700/80">
                                    {{ $author->country->common_name }}
                                </span>
                            </flux:table.cell>

                            {{-- Acciones --}}
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    <flux:button variant="ghost" size="sm" icon="eye" :href="route('livewire.authors.show', $author)" class="text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400" wire:navigate />
                                    
                                    <flux:button variant="ghost" size="sm" icon="pencil" :href="route('livewire.authors.edit', $author)" class="text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400" wire:navigate />
                                    
                                    <flux:button variant="ghost" size="sm" icon="trash" 
                                        wire:click="delete({{ $author->id }})" 
                                        wire:confirm="¿Estás seguro de que deseas eliminar este autor?" 
                                        class="text-zinc-400 hover:text-red-600 dark:hover:text-red-400"
                                    />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5">
                                <div class="flex flex-col items-center justify-center py-20 text-center">
                                    <div class="relative mb-6">
                                        <div class="absolute inset-0 bg-zinc-100 dark:bg-zinc-800 rounded-full scale-150 blur-xl opacity-50"></div>
                                        <div class="relative h-16 w-16 rounded-2xl bg-white dark:bg-zinc-800 flex items-center justify-center border border-zinc-200 dark:border-zinc-700 shadow-sm">
                                            <flux:icon name="users" class="h-8 w-8 text-zinc-400 dark:text-zinc-500" variant="outline" />
                                        </div>
                                    </div>
                                    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 italic">No hay autores registrados</h3>
                                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 max-w-xs mx-auto">
                                        Parece que aún no hay autores que coincidan con "{{ $search }}".
                                    </p>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="px-6 py-4 border-t border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/20">
                {{ $authors->links() }}
            </div>
        </flux:card>
    </flux:container>
</div>
