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

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Ajustes para el control de TomSelect */
        .ts-control {
            border: 1px solid var(--color-zinc-200) !important;
            border-bottom-color: rgb(212 212 216 / 0.8) !important;
            border-radius: 0.5rem !important;
            padding: 0.45rem 0.75rem !important;
            background-color: transparent !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            min-height: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            color: var(--color-zinc-800) !important;
            gap: 0.5rem !important;
            position: relative !important;
        }

        .ts-control > * {
            vertical-align: baseline !important;
            display: inline-flex !important;
        }

        .ts-control input {
            color: inherit !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            width: auto !important;
            flex: 1 !important;
            min-width: 4px !important;
        }

        /* Ocultar el input/cursor cuando ya hay algo seleccionado y no se tiene el foco */
        .ts-wrapper.single.has-items:not(.focus) .ts-control input {
            opacity: 0 !important;
            position: absolute !important;
        }

        .ts-wrapper.single.has-items.focus .ts-control .item {
            opacity: 0.5 !important;
        }

        .dark .ts-control {
            border-color: rgba(255,255,255,0.1) !important;
            color: var(--color-zinc-300) !important;
            background-color: var(--color-zinc-800) !important;
        }

        .dark .ts-control input {
            color: var(--color-zinc-200) !important;
        }

        .dark .ts-control input::placeholder {
            color: var(--color-zinc-500) !important;
        }

        .ts-dropdown {
            border-radius: 0.5rem !important;
            border: 1px solid var(--color-zinc-200) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
            margin-top: 4px !important;
            z-index: 100 !important;
        }

        .ts-dropdown-content {
            max-height: 200px !important;
            overflow-y: auto !important;
        }

        .dark .ts-dropdown {
            background-color: var(--color-zinc-800) !important;
            border-color: var(--color-zinc-700) !important;
            color: white;
        }
        .ts-dropdown .option:hover, .ts-dropdown .option.active {
            background-color: var(--color-zinc-100) !important;
            color: inherit !important;
        }
        .dark .ts-dropdown .option:hover, .dark .ts-dropdown .option.active {
            background-color: rgba(255,255,255,0.1) !important;
        }
        .ts-control, .ts-dropdown .option {
            font-size: 0.875rem !important;
        }
    </style>
    @endpush

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
