<x-layouts::clean :title="__('Seleccionar Arquitectura')">
    <div class="fixed top-0 right-0 p-6 z-50">
        @auth
            <flux:dropdown position="bottom" align="end">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                        />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                    {{-- <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item> --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                                data-test="logout-button"
                            >
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
        @else
            <div class="flex items-center gap-4">
                <flux:button :href="route('login')" variant="ghost" size="sm">Log in</flux:button>
                @if (Route::has('register'))
                    <flux:button :href="route('register')" variant="primary" size="sm">Register</flux:button>
                @endif
            </div>
        @endauth
    </div>

    <div class="flex flex-col items-center justify-center min-h-[60vh] py-12 px-4 sm:px-6 lg:px-8">
        @auth
            <div class="max-w-4xl w-full space-y-8 text-center">
                <div>
                    <flux:heading size="xl" class="mb-2">¡Bienvenido al Panel Admin!</flux:heading>
                    <flux:text size="lg">Selecciona la arquitectura con la que deseas gestionar el sistema.</flux:text>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                    <!-- MVC Card -->
                    <a href="{{ route('switch.mode', 'mvc') }}" class="group relative flex flex-col items-center p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl transition-all hover:scale-105 hover:shadow-2xl hover:border-blue-500">
                        <div class="flex size-20 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-600 mb-6">
                            <flux:icon name="circle-stack" class="size-12" />
                        </div>
                        <flux:heading size="lg" class="mb-2">Arquitectura MVC</flux:heading>
                        <flux:text class="text-center mb-6">Desarrollo tradicional con Blade, Controladores y recarga de página.</flux:text>
                        <div class="mt-auto px-6 py-2 rounded-full bg-blue-600 text-white font-medium group-hover:bg-blue-700 transition-colors">
                            Elegir MVC
                        </div>
                    </a>

                    <!-- Livewire Card -->
                    <a href="{{ route('switch.mode', 'livewire') }}" class="group relative flex flex-col items-center p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl transition-all hover:scale-105 hover:shadow-2xl hover:border-purple-500">
                        <div class="flex size-20 items-center justify-center rounded-2xl bg-purple-500/10 text-purple-600 mb-6">
                            <flux:icon name="bolt" class="size-12" />
                        </div>
                        <flux:heading size="lg" class="mb-2">Arquitectura Livewire</flux:heading>
                        <flux:text class="text-center mb-6">Experiencia reactiva moderna, SPA-like, sin salir de PHP.</flux:text>
                        <div class="mt-auto px-6 py-2 rounded-full bg-purple-600 text-white font-medium group-hover:bg-purple-700 transition-colors">
                            Elegir Livewire
                        </div>
                    </a>
                </div>
                
                <flux:text size="sm" class="mt-8">Podrás cambiar de modalidad en cualquier momento desde el sidebar.</flux:text>
            </div>
        @else
            <div class="max-w-6xl w-full mx-auto">
                <div class="text-center mb-12">
                    <flux:heading size="xl" class="mb-2">Biblioteca Central</flux:heading>
                    <flux:text size="lg">Explora nuestro catálogo público de libros. Inicia sesión para gestionar el contenido.</flux:text>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $books = \App\Models\Book::with('autor')->latest()->take(6)->get();
                    @endphp

                    @forelse($books as $book)
                        <flux:card class="flex flex-col h-full bg-white dark:bg-zinc-900 shadow-sm border-zinc-100 dark:border-zinc-800">
                            <div class="flex-1">
                                <flux:heading size="lg" class="mb-1">{{ $book->titulo }}</flux:heading>
                                <flux:text variant="subtle" class="mb-4">Por {{ $book->autor->nombre_completo }}</flux:text>
                                <flux:text size="sm" class="line-clamp-3">
                                    {{ $book->descripcion ?? 'Este ejemplar forma parte de nuestra colección histórica sin descripción detallada.' }}
                                </flux:text>
                            </div>
                            <div class="mt-6 pt-6 border-t border-zinc-50 dark:border-zinc-800 flex justify-between items-center">
                                <flux:badge variant="subtle" size="sm">{{ $book->año_publicacion }}</flux:badge>
                                <flux:button variant="ghost" size="sm">Ficha Completa</flux:button>
                            </div>
                        </flux:card>
                    @empty
                        <div class="col-span-full py-12">
                            <flux:card class="text-center p-12 bg-zinc-50 dark:bg-zinc-900/50 border-dashed">
                                <flux:icon name="book-open" class="size-12 mx-auto text-zinc-300 mb-4" />
                                <flux:heading size="lg">Catálogo Vacío</flux:heading>
                                <flux:text>Todavía no se han registrado libros en la base de datos.</flux:text>
                            </flux:card>
                        </div>
                    @endforelse
                </div>

                <div class="mt-16 bg-blue-50 dark:bg-blue-900/20 rounded-3xl p-8 text-center border border-blue-100 dark:border-blue-900/30">
                    <flux:heading size="lg" class="mb-2">¿Eres Administrador?</flux:heading>
                    <flux:text class="mb-6">Inicia sesión para acceder a los paneles de gestión MVC y Livewire.</flux:text>
                    <div class="flex justify-center gap-4">
                        <flux:button :href="route('login')" variant="primary">Iniciar Sesión</flux:button>
                        <flux:button :href="route('register')" variant="ghost">Crear Cuenta</flux:button>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</x-layouts::clean>
