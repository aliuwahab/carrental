<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 font-medium mb-4" wire:navigate>
                    <div class="flex items-center space-x-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">RG</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</span>
                    </div>
                </a>

                <div class="flex flex-col gap-6">
                    <div class="rounded-2xl border border-gray-200 bg-white text-gray-800 shadow-xl">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
