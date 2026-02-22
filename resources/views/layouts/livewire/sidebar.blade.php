<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" name_mode="Livewire" href="{{ route('authors.index') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')" class="grid">
            <flux:sidebar.item icon="squares-2x2" :href="route('authors.index')" :current="request()->is('authors*')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Módulos')" class="grid">
            <flux:sidebar.item icon="users" :href="route('authors.index')" :current="request()->routeIs('authors.*')" wire:navigate>
                {{ __('Autores') }}
                <flux:badge size="sm" color="purple" inset="top bottom" class="ml-auto">LW</flux:badge>
            </flux:sidebar.item>

            <flux:sidebar.item icon="book-open" href="#" class="opacity-50">
                {{ __('Libros') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="arrow-left-start-on-rectangle" :href="route('home')" wire:navigate>
            {{ __('Cambiar Arquitectura') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

</flux:sidebar>
