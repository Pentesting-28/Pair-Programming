<x-layouts::mvc :title="__('Autores (MVC)')">
    <flux:container>
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl" level="1">Administración de Autores</flux:heading>
                <flux:subheading>Modo MVC Tradicional</flux:subheading>
            </div>

            <flux:button variant="primary" :href="route('mvc.authors.create')" icon="plus">
                Nuevo Autor
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" class="mb-6">
                {{ session('success') }}
            </flux:callout>
        @endif

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
                        <flux:table.row>
                            <flux:table.cell class="font-medium">{{ $author->nombre }}</flux:table.cell>
                            <flux:table.cell>{{ $author->apellido }}</flux:table.cell>
                            <flux:table.cell>{{ $author->fecha_nacimiento ? \Carbon\Carbon::parse($author->fecha_nacimiento)->format('d/m/Y') : '-' }}</flux:table.cell>
                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" size="sm" icon="pencil" :href="route('mvc.authors.edit', $author)" />
                                    
                                    <form action="{{ route('mvc.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button variant="ghost" size="sm" icon="trash" type="submit" />
                                    </form>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center py-8 text-zinc-500">
                                No hay autores registrados.
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
</x-layouts::mvc>
