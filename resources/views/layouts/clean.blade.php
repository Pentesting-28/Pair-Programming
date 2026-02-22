<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="flex flex-col items-center justify-center min-h-screen">
            {{ $slot }}
        </div>

        @fluxScripts
    </body>
</html>
