<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-3 mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-xl">
                <span class="text-white font-bold text-2xl">RG</span>
            </div>
            <span class="text-3xl font-bold text-gray-900">{{ config('app.name') }}</span>
        </a>

        <!-- Error Content -->
        <div class="bg-white rounded-3xl shadow-2xl p-12 border border-gray-100">
            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                Oops! The page you're looking for seems to have taken a detour. Let's get you back on track.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Home
                </a>
                <a href="{{ route('vehicles.index') }}" 
                   class="inline-flex items-center justify-center bg-white border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-xl hover:bg-gray-50 hover:border-blue-300 transition-all duration-300 font-semibold">
                    Browse Vehicles
                </a>
            </div>
        </div>
        
        <p class="mt-8 text-gray-500 text-sm">
            Need help? <a href="mailto:{{ config('mail.from.address') }}" class="text-blue-600 hover:text-blue-700 font-medium">Contact Support</a>
        </p>
    </div>
</body>
</html>
