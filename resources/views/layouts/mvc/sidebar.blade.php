<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('mvc.dashboard') }}" />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')" class="grid">
            <flux:sidebar.item icon="squares-2x2" :href="route('mvc.dashboard')" :current="request()->routeIs('mvc.dashboard')">
                {{ __('Dashboard') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Módulos')" class="grid">
            <flux:navlist>
                <flux:navlist.group expandable :heading="__('Biblioteca')" icon="building-library" :expanded="request()->routeIs('mvc.authors.*')">
                    <flux:navlist.item icon="users" :href="route('mvc.authors.index')" :current="request()->routeIs('mvc.authors.*')">
                        {{ __('Autores') }}
                    </flux:navlist.item>

                    <flux:navlist.item icon="book-open" href="#" class="opacity-50">
                        {{ __('Libros') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="arrow-left-start-on-rectangle" :href="route('home')">
            {{ __('Cambiar Arquitectura') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

</flux:sidebar>
