<x-layouts::mvc :title="__('Detalle de Autor (MVC)')">
    <flux:container max-width="lg">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <flux:button variant="subtle" size="sm" icon="arrow-left" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200" :href="route('mvc.authors.index')">
                    Volver al listado
                </flux:button>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <div class="author-image-preview-wrapper rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center border-2 border-white dark:border-zinc-700 shadow-xl overflow-hidden relative group">
                    @if ($author->photo_path)
                        <img src="{{ $author->photo_url }}" alt="Foto de {{ $author->name }}" />
                    @else
                        <div class="flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-500">
                            <flux:icon name="camera" class="w-10 h-10 opacity-50" variant="outline" />
                        </div>
                    @endif
                </div>
                <div class="flex flex-col gap-1">
                    <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">
                        {{ $author->name }} {{ $author->last_name }}
                    </flux:heading>
                    <div class="flex items-center gap-2 text-zinc-500 dark:text-zinc-400">
                        <img src="{{ $author->country->flag_url }}" class="h-3 w-5 rounded-sm object-cover" />
                        <span class="text-sm">{{ $author->country->common_name }}</span>
                        <span class="text-zinc-300 dark:text-zinc-600">•</span>
                        <span class="text-sm italic">Nacido el {{ $author->birth_date ? \Carbon\Carbon::parse($author->birth_date)->format('d/m/Y') : 'Desconocido' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8">
            {{-- Sección de Libros --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg" class="font-semibold">Libros Publicados ({{ $author->books->count() }})</flux:heading>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($author->books as $book)
                        <flux:card class="p-4 hover:shadow-md transition-shadow duration-200 border-zinc-200/60 dark:border-zinc-700/50 group">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 shrink-0 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <flux:icon name="book-open" variant="outline" />
                                </div>
                                <div class="flex flex-col gap-0.5 min-w-0">
                                    <h4 class="font-semibold text-zinc-900 dark:text-white truncate" title="{{ $book->title }}">
                                        {{ $book->title }}
                                    </h4>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">ISBN: {{ $book->isbn }}</p>
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ $book->num_pages }} páginas</p>
                                </div>
                            </div>
                        </flux:card>
                    @empty
                        <div class="col-span-full py-12 flex flex-col items-center justify-center border-2 border-dashed border-zinc-200 dark:border-zinc-700/50 rounded-2xl bg-zinc-50/50 dark:bg-zinc-800/20 text-center px-6">
                            <flux:icon name="book-open" class="h-10 w-10 text-zinc-300 mb-3" variant="outline" />
                            <h3 class="font-medium text-zinc-500 dark:text-zinc-400 italic">No hay libros registrados para este autor.</h3>
                            <flux:button variant="subtle" size="sm" class="mt-4" :href="route('mvc.books.create', ['author_id' => $author->id])">
                                Registrar primer libro
                            </flux:button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-12 flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <flux:button variant="ghost" :href="route('mvc.authors.index')" class="text-zinc-500">
                Cerrar vista
            </flux:button>
            <div class="flex gap-2">
                <flux:button variant="subtle" icon="pencil" :href="route('mvc.authors.edit', $author)">
                    Editar Perfil
                </flux:button>
            </div>
        </div>
    </flux:container>
</x-layouts::mvc>
