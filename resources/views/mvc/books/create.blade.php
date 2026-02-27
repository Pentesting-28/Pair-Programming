<x-layouts::mvc :title="__('Nuevo Libro (MVC)')">
    <flux:container max-width="lg">
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <flux:button variant="subtle" size="sm" icon="arrow-left" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200" :href="route('mvc.books.index')">
                    Volver
                </flux:button>
            </div>
            <div class="flex flex-col gap-1">
                <flux:heading size="xl" level="1" class="font-bold tracking-tight text-zinc-900 dark:text-white">Crear Nuevo Libro</flux:heading>
                <flux:subheading class="text-zinc-500 dark:text-zinc-400">Registra un nuevo ejemplar en la colección de la biblioteca.</flux:subheading>
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

            <form action="{{ route('mvc.books.store') }}" method="POST" class="flex flex-col">
                @csrf
                
                <div class="p-6 space-y-6">

                    <flux:field>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <flux:label class="!mb-0">Autor</flux:label>
                            
                            <flux:modal.trigger name="create-author-modal">
                                <flux:button variant="subtle" size="xs" icon="plus" class="text-blue-600 hover:text-blue-700 bg-blue-50/50 hover:bg-blue-50 border-blue-100 hover:border-blue-200">
                                    Crear Autor
                                </flux:button>
                            </flux:modal.trigger>
                        </div>
                        
                        <select id="author-select" name="author_id" required class="w-full">
                            <option value=""></option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }} {{ $author->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>Título del Libro</flux:label>
                        <flux:input name="title" value="{{ old('title') }}" required placeholder="Ej. Cien años de soledad" />
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <flux:field>
                            <flux:label>ISBN</flux:label>
                            <flux:input name="isbn" value="{{ old('isbn') }}" required placeholder="Ej. 978-3-16-148410-0" />
                            @error('isbn')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </flux:field>

                        <flux:field>
                            <flux:label>Número de Páginas</flux:label>
                            <flux:input type="number" name="num_pages" value="{{ old('num_pages') }}" required placeholder="Ej. 432" min="1" />
                            @error('num_pages')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                        </flux:field>
                    </div>

                    
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-zinc-200/80 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/20">
                    <flux:button variant="ghost" :href="route('mvc.books.index')" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="shadow-sm">
                        Guardar Libro
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </flux:container>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Corregir visibilidad del placeholder en TomSelect */
        .ts-wrapper.single .ts-control input::placeholder {
            color: #71717a !important; /* zinc-500 */
            opacity: 1;
        }
        .dark .ts-wrapper.single .ts-control input::placeholder {
            color: #a1a1aa !important; /* zinc-400 */
        }
        
        /* Ocultar el item vacío si TomSelect lo llega a renderizar */
        .ts-wrapper .item[data-value=""], 
        .ts-wrapper .option[data-value=""] {
            display: none !important;
        }

        /* Asegurar que el input no se oculte si el "item" seleccionado es vacío */
        .ts-wrapper.single.full.has-items:not(.ts-has-value) .ts-control > input {
            display: block !important;
            opacity: 1 !important;
            position: relative !important;
        }

        /* Estilos para homogenizar TomSelect con Flux Input */
        .ts-wrapper.w-full .ts-control {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #e4e4e7 !important; /* zinc-200 */
            background-color: transparent !important;
            min-height: 40px !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .dark .ts-wrapper.w-full .ts-control {
            border-color: rgba(255, 255, 255, 0.1) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        .ts-wrapper.w-full.focus .ts-control {
            border-color: #3b82f6 !important; /* blue-500 */
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
        }
        .ts-wrapper.w-full .ts-control input {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    {{-- Modal para Crear Autor Rápido --}}
    <flux:modal name="create-author-modal" focusable class="max-w-2xl">
        <form id="ajax-author-form" action="{{ route('mvc.authors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <flux:heading size="lg">Nuevo Autor</flux:heading>
                <flux:subheading>Crea un autor rápidamente para asociarlo al libro sin perder tu progreso.</flux:subheading>

                <div id="author-success-message" class="hidden mt-6">
                    <flux:callout variant="success" class="shadow-sm border-emerald-200/50 dark:border-emerald-900/50">
                        <span id="author-success-text"></span>
                    </flux:callout>
                </div>
            </div>

            <div id="author-ajax-errors" class="hidden">
                <flux:callout variant="danger">
                    <ul class="list-disc list-inside text-xs"></ul>
                </flux:callout>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input name="name" required placeholder="Ej. Gabriel" />
                </flux:field>

                <flux:field>
                    <flux:label>Apellido</flux:label>
                    <flux:input name="last_name" required placeholder="Ej. García Márquez" />
                </flux:field>

                <flux:field>
                    <flux:label>Nacimiento</flux:label>
                    <flux:input type="date" name="birth_date" />
                </flux:field>

                <flux:field>
                    <flux:label>País</flux:label>
                    {{-- El select se verá como un flux:input por el CSS inyectado --}}
                    <select id="modal-country-select" name="country_id" required class="w-full">
                        <option value=""></option>
                    </select>
                </flux:field>
            </div>

            <div class="w-full h-px bg-zinc-200/60 dark:bg-zinc-700/50 my-2"></div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-start">
                <div class="sm:col-span-3">
                    <flux:label class="mb-3">Previsualización</flux:label>
                    <div id="image-preview-container" class="relative group author-image-preview-wrapper mx-auto sm:mx-0">
                        <div class="w-full h-full rounded-xl border-2 border-dashed border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 flex items-center justify-center overflow-hidden transition-all group-hover:border-blue-400">
                            <img id="image-preview" src="" class="hidden" />
                            <div id="image-placeholder" class="flex flex-col items-center justify-center text-zinc-400">
                                <flux:icon name="camera" class="w-6 h-6 mb-1 opacity-50" variant="outline" />
                                <span class="text-[8px] uppercase font-bold tracking-wider">Subir</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-9">
                    <flux:field>
                        <flux:label>Fotografía del Autor</flux:label>
                        <flux:input type="file" name="photo_path" id="photo_input" accept="image/*" />
                        <flux:description class="mt-2">Formatos: JPG, PNG, WebP. Resolución recomendada: 400x400px (Máx 2MB).</flux:description>
                    </flux:field>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-200/60 dark:border-zinc-700/50">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" id="btn-save-author" class="px-6">
                    Guardar Autor
                </flux:button>
            </div>
        </form>
    </flux:modal>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var countryData = @json($countries ?? []);
            var initialAuthor = @json([old('author_id')]).filter(Boolean);
            
            // TomSelect Autor Principal
            const authorSelect = new TomSelect('#author-select', {
                placeholder: 'Busca y selecciona un autor...',
                allowEmptyOption: true,
                maxOptions: null,
                items: initialAuthor,
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">No se encontraron autores para "' + escape(data.input) + '"</div>';
                    }
                }
            });

            // TomSelect País en Modal
            const modalCountrySelect = new TomSelect('#modal-country-select', {
                valueField: 'id',
                labelField: 'common_name',
                searchField: 'common_name',
                options: countryData,
                placeholder: 'Selecciona país...',
                allowEmptyOption: true,
                maxOptions: null,
                items: [],
                render: {
                    option: function(data, escape) {
                        return `<div class="flex items-center gap-2">
                                    <img src="${escape(data.flagUrl)}" class="h-3 w-5 rounded-sm object-cover" />
                                    <span class="text-sm">${escape(data.common_name)}</span>
                                </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="flex items-center gap-2">
                                    <img src="${escape(data.flagUrl)}" class="h-3 w-5 rounded-sm object-cover" />
                                    <span>${escape(data.common_name)}</span>
                                </div>`;
                    }
                }
            });

            // Lógica AJAX para Crear Autor
            const authorForm = document.getElementById('ajax-author-form');
            const errorContainer = document.getElementById('author-ajax-errors');
            const successContainer = document.getElementById('author-success-message');
            const successText = document.getElementById('author-success-text');
            const btnSave = document.getElementById('btn-save-author');
            const authorModal = document.querySelector('[data-modal="create-author-modal"]');

            if (authorForm) {
                authorForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    btnSave.disabled = true;
                    btnSave.textContent = 'Guardando...';
                    errorContainer.classList.add('hidden');
                    successContainer.classList.add('hidden');

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 200 || res.status === 201) {
                            // Éxito: Añadir al TomSelect, seleccionarlo
                            authorSelect.addOption({ value: res.body.id, text: res.body.name });
                            authorSelect.addItem(res.body.id);
                            
                            // Mostrar mensaje de éxito en la vista principal
                            successText.textContent = res.body.message || 'Autor creado con éxito.';
                            successContainer.classList.remove('hidden');
                            
                            // Limpiar
                            authorForm.reset();
                            if (modalCountrySelect) modalCountrySelect.clear();
                            resetImagePreview();
                            
                            // Cerrar modal vía dispatch de Flux
                            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'create-author-modal' }));
                            
                            // Scroll suave al mensaje de éxito
                            successContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        } else {
                            // Error: Mostrar en el callout de la modal
                            showErrors(res.body.errors || { error: [res.body.message || 'Error al crear autor'] });
                        }
                    })
                    .catch(err => {
                        console.error('Error AJAX:', err);
                        showErrors({ error: ['Ocurrió un error inesperado al procesar la solicitud.'] });
                    })
                    .finally(() => {
                        btnSave.disabled = false;
                        btnSave.textContent = 'Guardar Autor';
                    });
                });
            }

            function showErrors(errors) {
                const list = errorContainer.querySelector('ul');
                list.innerHTML = '';
                Object.values(errors).forEach(errGroup => {
                    errGroup.forEach(msg => {
                        const li = document.createElement('li');
                        li.textContent = msg;
                        list.appendChild(li);
                    });
                });
                errorContainer.classList.remove('hidden');
            }

            // Lógica Previsualizador en Modal
            const photoInput = document.getElementById('photo_input');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholder = document.getElementById('image-placeholder');

            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            imagePlaceholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            function resetImagePreview() {
                imagePreview.src = '';
                imagePreview.classList.add('hidden');
                imagePlaceholder.classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-layouts::mvc>
