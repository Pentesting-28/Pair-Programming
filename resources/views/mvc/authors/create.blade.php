<x-layouts::mvc :title="__('Nuevo Autor (MVC)')">
    <flux:container max-width="xl">
        <div class="mb-6">
            <flux:button variant="ghost" icon="arrow-left" class="-ml-2 mb-2" :href="route('mvc.authors.index')">Volver</flux:button>
            <flux:heading size="xl" level="1">Crear Nuevo Autor</flux:heading>
        </div>

        <flux:card>
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

            <form action="{{ route('mvc.authors.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input name="name" value="{{ old('name') }}" required />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input name="last_name" value="{{ old('last_name') }}" required />
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de Nacimiento</flux:label>
                    <flux:input type="date" name="birth_date" value="{{ old('birth_date') }}" />
                </flux:field>

                <flux:field>
                    <flux:label>País</flux:label>
                    <flux:select name="country_id" value="{{ old('country_id') }}" required>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->common_name }}</option>
                        @endforeach
                    </flux:select>
                    @error('country_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Foto</flux:label>
                    <flux:input type="file" name="photo_path" value="{{ old('photo_path') }}" />
                </flux:field>

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('mvc.authors.index')">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Guardar Autor</flux:button>
                </div>
            </form>
        </flux:card>
    </flux:container>
</x-layouts::mvc>
