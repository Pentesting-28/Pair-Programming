<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" name_mode="Livewire" href="{{ route('livewire.dashboard') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')" class="grid">
            <flux:sidebar.item icon="squares-2x2" :href="route('livewire.dashboard')" :current="request()->routeIs('livewire.dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Módulos')" class="grid">
            <flux:navlist>
                <flux:navlist.group expandable :heading="__('Biblioteca')" icon="building-library" :expanded="request()->routeIs('livewire.authors.*')">
                    <flux:navlist.item icon="users" :href="route('livewire.authors.index')" :current="request()->routeIs('livewire.authors.*')" wire:navigate>
                        {{ __('Autores') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="book-open" :href="route('livewire.books.index')" :current="request()->routeIs('livewire.books.*')" wire:navigate>
                        {{ __('Libros') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="arrow-left-start-on-rectangle" :href="route('home')" wire:navigate>
            {{ __('Cambiar Arquitectura') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

</flux:sidebar>
