<x-layouts::mvc :title="__('Editar Autor (MVC)')">
    <flux:container max-width="xl">
        <div class="mb-6">
            <flux:button variant="ghost" icon="arrow-left" class="-ml-2 mb-2" :href="route('mvc.authors.index')">Volver</flux:button>
            <flux:heading size="xl" level="1">Editar Autor</flux:heading>
            <flux:subheading>Modo MVC Tradicional - {{ $author->name }} {{ $author->last_name }}</flux:subheading>
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
            <form action="{{ route('mvc.authors.update', $author) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input name="name" value="{{ old('name', $author->name) }}" required />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input name="last_name" value="{{ old('last_name', $author->last_name) }}" required />
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de Nacimiento</flux:label>
                    <flux:input type="date" name="birth_date" value="{{ old('birth_date', $author->birth_date) }}" />
                    @error('birth_date')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>País</flux:label>
                    <select id="country-select" name="country_id" required class="w-full">
                        <option value=""></option>
                    </select>
                    @error('country_id')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Foto</flux:label>
                    <flux:input type="file" name="photo_path" />
                    @error('photo_path')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </flux:field>
                <div class="mt-4">
                    <img src="{{ $author->photo_url }}" alt="{{ $author->name }}" class="w-32 h-32 object-cover rounded-lg shadow-sm">
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button variant="ghost" :href="route('mvc.authors.index')">Cancelar</flux:button>
                    <flux:button type="submit" variant="primary">Actualizar Autor</flux:button>
                </div>
            </form>
        </flux:card>
    </flux:container>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var countryData = @json($countries);
            var initialItems = @json([old('country_id', $author->country_id ?? '')]).filter(Boolean);
            
            new TomSelect('#country-select', {
                valueField: 'id',
                labelField: 'common_name',
                searchField: 'common_name',
                options: countryData,
                items: initialItems,
                placeholder: 'Selecciona un país...',
                maxOptions: null,
                render: {
                    option: function(data, escape) {
                        return `<div class="flex items-center gap-3">
                                    <img src="${escape(data.flagUrl)}" class="h-4 w-6 rounded-sm object-cover shadow-xs border border-zinc-200/50" />
                                    <span>${escape(data.common_name)}</span>
                                </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="flex items-center gap-2">
                                    <img src="${escape(data.flagUrl)}" class="h-4 w-6 rounded-sm object-cover shadow-xs border border-zinc-200/50" />
                                    <span>${escape(data.common_name)}</span>
                                </div>`;
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts::mvc>
