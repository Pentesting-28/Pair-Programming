<x-layouts::mvc :title="__('Nuevo Autor (MVC)')">
    <flux:container max-width="lg">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <flux:button variant="subtle" size="sm" icon="arrow-left" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200" :href="route('mvc.authors.index')">
                    Volver
                </flux:button>
            </div>
            <div class="flex flex-col gap-1">
                <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">Crear Nuevo Autor</flux:heading>
                <flux:subheading class="text-zinc-500 dark:text-zinc-400">Añade la información necesaria para registrar un nuevo autor en la plataforma.</flux:subheading>
            </div>
        </div>

        <flux:card class="p-0 overflow-hidden shadow-sm">
            @if($errors->any())
                <div class="p-6 pb-0">
                    <flux:callout variant="danger" class="shadow-sm border-red-200/50 dark:border-red-900/50">
                        <flux:heading class="font-semibold text-red-800 dark:text-red-200">Errores de validación</flux:heading>
                        <ul class="list-disc list-inside mt-2 text-sm text-red-700 dark:text-red-300">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </flux:callout>
                </div>
            @endif

            <form action="{{ route('mvc.authors.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
                @csrf
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>Nombre</flux:label>
                            <flux:input name="name" value="{{ old('name') }}" required placeholder="Ej. Gabriel" />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </flux:field>

                        <flux:field>
                            <flux:label>Apellido</flux:label>
                            <flux:input name="last_name" value="{{ old('last_name') }}" required placeholder="Ej. García Márquez" />
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>Fecha de Nacimiento</flux:label>
                            <flux:input type="date" name="birth_date" value="{{ old('birth_date') }}" />
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
                    </div>
                    
                    <div class="w-full h-px bg-zinc-200/80 dark:bg-zinc-800 my-2"></div>

                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <div id="image-preview-container" class="relative group author-image-preview-wrapper">
                            <div class="w-full h-full rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center overflow-hidden transition-all group-hover:border-zinc-300 dark:group-hover:border-zinc-600">
                                <img id="image-preview" src="" class="hidden" />
                                <div id="image-placeholder" class="flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-zinc-500 dark:group-hover:text-zinc-400">
                                    <flux:icon name="camera" class="w-10 h-10 mb-2 opacity-50" variant="outline" />
                                    <span class="text-[10px] uppercase tracking-wider font-semibold">Subir foto</span>
                                </div>
                            </div>
                            <button type="button" id="remove-image" class="hidden absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors z-20">
                                <flux:icon name="x-mark" class="w-4 h-4" variant="mini" />
                            </button>
                        </div>
                        
                        <flux:field class="flex-1 w-full">
                            <flux:label>Fotografía</flux:label>
                            <flux:input type="file" name="photo_path" id="photo_input" accept="image/*" />
                            <flux:description class="mt-1 text-xs">Formatos recomendados: JPG, PNG o WebP. Tamaño máximo 2MB.</flux:description>
                            @error('photo_path')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </flux:field>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/20">
                    <flux:button variant="ghost" :href="route('mvc.authors.index')" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="shadow-sm">
                        Guardar Autor
                    </flux:button>
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
            
            console.log(countryData);
            
            new TomSelect('#country-select', {
                valueField: 'id',
                labelField: 'common_name',
                searchField: 'common_name',
                options: countryData,
                items: initialItems,
                placeholder: 'Selecciona un país...',
                maxOptions: null, // Permitir ver todos los países (son aprox 250)
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

            // Lógica para el previsualizador de imagen
            const photoInput = document.getElementById('photo_input');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholder = document.getElementById('image-placeholder');
            const removeImageBtn = document.getElementById('remove-image');

            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            imagePlaceholder.classList.add('hidden');
                            removeImageBtn.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function() {
                    photoInput.value = '';
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                    imagePlaceholder.classList.remove('hidden');
                    this.classList.add('hidden');
                });
            }
        });
    </script>
    @endpush
</x-layouts::mvc>
