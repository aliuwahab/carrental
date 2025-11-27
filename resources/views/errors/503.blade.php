<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance Mode | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
        <div class="inline-flex items-center space-x-3 mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-xl">
                <span class="text-white font-bold text-2xl">RG</span>
            </div>
            <span class="text-3xl font-bold text-gray-900">{{ config('app.name') }}</span>
        </div>

        <!-- Error Content -->
        <div class="bg-white rounded-3xl shadow-2xl p-12 border border-gray-100">
            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-6xl font-bold text-gray-900 mb-4">503</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">We'll Be Right Back</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                We're currently performing scheduled maintenance to improve your experience. We'll be back shortly!
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                <p class="text-sm text-blue-800">
                    <strong>Estimated Return:</strong> A few minutes
                </p>
            </div>
            
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Check Again
            </button>
        </div>
        
        <p class="mt-8 text-gray-500 text-sm">
            For urgent inquiries: <a href="mailto:{{ config('mail.from.address') }}" class="text-blue-600 hover:text-blue-700 font-medium">{{ config('mail.from.address') }}</a>
        </p>
    </div>
</body>
</html>
