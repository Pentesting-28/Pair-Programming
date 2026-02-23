<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
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

            /* Estilos para el Previsualizador de Imágenes */
            .author-image-preview-wrapper {
                width: 8rem !important;
                height: 8rem !important;
                min-width: 8rem !important;
                min-height: 8rem !important;
                flex-shrink: 0 !important;
            }

            .author-image-preview-wrapper img:not(.hidden) {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                display: block !important;
            }
            .author-image-preview-wrapper .hidden {
                display: none !important;
            }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @include('layouts.mvc.sidebar')

        <!-- Mobile Header (Pure Blade) -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <x-desktop-user-menu :name="auth()->user()->name" />
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @stack('scripts')
        @fluxScripts
    </body>
</html>
