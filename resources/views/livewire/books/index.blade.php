<?php

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.livewire')] #[Title('Administración de Libros')] class extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Book $book)
    {
        try {
            $book->delete();
            session()->flash('success', 'Libro eliminado con éxito.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function with()
    {
        return [
            'books' => Book::with('author')
                ->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('isbn', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ];
    }
}; ?>

<div>
    <flux:container>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div class="flex flex-col gap-1">
                <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">Administración de Libros</flux:heading>
                <flux:subheading class="text-zinc-500 dark:text-zinc-400">Modo Livewire Reactivo</flux:subheading>
            </div>

            <flux:button variant="primary" :href="route('livewire.books.new')" icon="plus" class="shadow-sm" wire:navigate>
                Nuevo Libro
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
                placeholder="Buscar libro por título o ISBN..." 
                icon="magnifying-glass" 
            />
        </div>

        <flux:card class="overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Id</flux:table.column>
                    <flux:table.column>Título</flux:table.column>
                    <flux:table.column>ISBN</flux:table.column>
                    <flux:table.column>Páginas</flux:table.column>
                    <flux:table.column>Autor</flux:table.column>
                    <flux:table.column align="end">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($books as $book)
                        <flux:table.row :key="$book->id" class="group hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors duration-200">
                            {{-- Columna ID --}}
                            <flux:table.cell class="font-medium text-zinc-600 dark:text-zinc-400">
                                {{ $book->id }}
                            </flux:table.cell>

                            {{-- Columna Título --}}
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $book->title }}
                            </flux:table.cell>

                            {{-- Columna ISBN --}}
                            <flux:table.cell class="text-zinc-600 dark:text-zinc-400">
                                {{ $book->isbn }}
                            </flux:table.cell>

                            {{-- Columna Páginas --}}
                            <flux:table.cell class="text-zinc-600 dark:text-zinc-400">
                                {{ $book->num_pages }}
                            </flux:table.cell>

                            {{-- Columna Autor --}}
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 shrink-0 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center border border-zinc-200/80 dark:border-zinc-700/80 overflow-hidden shadow-sm">
                                        @if($book->author->photo_url)
                                            <img src="{{ $book->author->photo_url }}" alt="Avatar de {{ $book->author->name }}" class="h-full w-full object-cover" />
                                        @else
                                            <flux:icon name="user" class="h-5 w-5 text-zinc-400" variant="outline" />
                                        @endif
                                    </div>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $book->author->name }} {{ $book->author->last_name }}
                                    </span>
                                </div>
                            </flux:table.cell>

                            {{-- Acciones --}}
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                    <flux:button variant="ghost" size="sm" icon="pencil" :href="route('livewire.books.edit', $book)" class="text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400" wire:navigate />
                                    
                                    <flux:button variant="ghost" size="sm" icon="trash" 
                                        wire:click="delete({{ $book->id }})" 
                                        wire:confirm="¿Estás seguro de que deseas eliminar este libro?" 
                                        class="text-zinc-400 hover:text-red-600 dark:hover:text-red-400"
                                    />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6">
                                <div class="flex flex-col items-center justify-center py-20 text-center">
                                    <div class="relative mb-6">
                                        <div class="absolute inset-0 bg-zinc-100 dark:bg-zinc-800 rounded-full scale-150 blur-xl opacity-50"></div>
                                        <div class="relative h-16 w-16 rounded-2xl bg-white dark:bg-zinc-800 flex items-center justify-center border border-zinc-200 dark:border-zinc-700 shadow-sm">
                                            <flux:icon name="book-open" class="h-8 w-8 text-zinc-400 dark:text-zinc-500" variant="outline" />
                                        </div>
                                    </div>
                                    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 italic">No hay libros registrados</h3>
                                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 max-w-xs mx-auto">
                                        Parece que aún no hay libros que coincidan con "{{ $search }}".
                                    </p>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="px-6 py-4 border-t border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/20">
                {{ $books->links() }}
            </div>
        </flux:card>
    </flux:container>
</div>
