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

                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <div id="image-preview-container" class="relative group author-image-preview-wrapper">
                            <div class="w-full h-full rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 flex items-center justify-center overflow-hidden transition-all group-hover:border-zinc-300 dark:group-hover:border-zinc-600">
                                <img id="image-preview" src="{{ $author->photo_url }}" class="{{ $author->photo_path ? '' : 'hidden' }}" />
                                <div id="image-placeholder" class="flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-zinc-500 dark:group-hover:text-zinc-400 {{ $author->photo_path ? 'hidden' : '' }}">
                                    <flux:icon name="camera" class="w-10 h-10 mb-2 opacity-50" variant="outline" />
                                    <span class="text-[10px] uppercase tracking-wider font-semibold">Subir foto</span>
                                </div>
                            </div>
                            <button type="button" id="remove-image" class="{{ $author->photo_path ? '' : 'hidden' }} absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors z-20">
                                <flux:icon name="x-mark" class="w-4 h-4" variant="mini" />
                            </button>
                        </div>
                        
                        <flux:field class="flex-1 w-full">
                            <flux:label>Fotografía</flux:label>
                            <input type="hidden" name="remove_photo" id="remove_photo_input" value="0">
                            <flux:input type="file" name="photo_path" id="photo_input" accept="image/*" />
                            <flux:description class="mt-1 text-xs">Deja este campo vacío para mantener la foto actual. Formatos: JPG, PNG, WebP.</flux:description>
                            @error('photo_path')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </flux:field>
                    </div>
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

            // Lógica para el previsualizador de imagen
            const photoInput = document.getElementById('photo_input');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholder = document.getElementById('image-placeholder');
            const removeImageBtn = document.getElementById('remove-image');
            const removePhotoInput = document.getElementById('remove_photo_input');
            const originalSrc = imagePreview ? imagePreview.src : '';
            const originalHasImage = imagePreview ? !imagePreview.classList.contains('hidden') : false;

            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        if (removePhotoInput) removePhotoInput.value = '0';
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
                    if (removePhotoInput) removePhotoInput.value = '1';
                    
                    // Si estamos editando y quitamos la selección nueva, volvemos al estado inicial
                    if (originalHasImage && this.dataset.action !== 'remove-original') {
                        // Si el usuario subió algo y le dio a X, volvemos a la original
                        // Pero si le dio a X sobre la original, ahí sí marcamos borrar
                    }
                    
                    // Lógica simplificada: si le dan a X, vaciamos todo y marcamos borrar
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
