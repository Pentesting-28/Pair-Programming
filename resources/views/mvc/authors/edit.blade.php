<x-layouts::mvc :title="__('Editar Autor (MVC)')">
    <flux:container max-width="xl">
        <div class="mb-6">
            <flux:button variant="ghost" icon="arrow-left" class="-ml-2 mb-2" :href="route('mvc.authors.index')">Volver</flux:button>
            <flux:heading size="xl" level="1">Editar Autor</flux:heading>
            <flux:subheading>Modo MVC Tradicional - {{ $author->nombre }} {{ $author->apellido }}</flux:subheading>
        </div>

        @if($errors->any())
            <flux:callout variant="danger" class="mb-6">
                <flux:heading>Errores de validación</flux:heading>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </flux:callout>
        @endif

        <flux:card>
            <form action="{{ route('mvc.authors.update', $author) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input name="nombre" value="{{ old('nombre', $author->nombre) }}" required />
                    @error('nombre')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input name="apellido" value="{{ old('apellido', $author->apellido) }}" required />
                    @error('apellido')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de Nacimiento</flux:label>
                    <flux:input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $author->fecha_nacimiento) }}" />
                    @error('fecha_nacimiento')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('mvc.authors.index')">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Actualizar Autor</flux:button>
                </div>
            </form>
        </flux:card>
    </flux:container>
</x-layouts::mvc>
