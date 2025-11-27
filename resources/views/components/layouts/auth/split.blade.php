<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-cyan-600"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center space-x-2 text-lg font-medium" wire:navigate>
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold">RG</span>
                    </div>
                    <span class="text-white font-bold text-xl">{{ config('app.name') }}</span>
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-3 font-medium lg:hidden mb-4" wire:navigate>
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-lg flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold">RG</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                        </div>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
