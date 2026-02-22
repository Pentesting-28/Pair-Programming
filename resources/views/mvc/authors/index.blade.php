<x-layouts::mvc :title="__('Autores (MVC)')">
    <flux:container>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div class="flex flex-col gap-1">
                <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">Administración de Autores</flux:heading>
                <flux:subheading class="text-zinc-500 dark:text-zinc-400">Modo MVC Tradicional</flux:subheading>
            </div>

            <flux:button variant="primary" :href="route('mvc.authors.create')" icon="plus" class="shadow-sm">
                Nuevo Autor
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" class="mb-6 shadow-sm border-emerald-200/50 dark:border-emerald-900/50">
                {{ session('success') }}
            </flux:callout>
        @endif

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
                        <flux:table.row class="group hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors duration-200">
                            {{-- Columna ID --}}
                            <flux:table.cell class="font-medium text-zinc-600 dark:text-zinc-400">
                                {{ $author->id }}
                            </flux:table.cell>

                            {{-- Columna Autor (Avatar + Nombre) --}}
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
                                    <flux:button variant="ghost" size="sm" icon="pencil" :href="route('mvc.authors.edit', $author)" class="text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400" />
                                    
                                    <form action="{{ route('mvc.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este autor?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button variant="ghost" size="sm" icon="trash" type="submit" class="text-zinc-400 hover:text-red-600 dark:hover:text-red-400" />
                                    </form>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-14 w-14 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4 border border-zinc-200/80 dark:border-zinc-700/80">
                                        <svg class="h-6 w-6 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Sin autores</h3>
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 max-w-sm">No se encontraron autores en la base de datos. Comienza añadiendo uno nuevo.</p>
                                    <div class="mt-4">
                                        <flux:button variant="ghost" size="sm" :href="route('mvc.authors.create')" icon="plus">
                                            Añadir Autor
                                        </flux:button>
                                    </div>
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
</x-layouts::mvc>
