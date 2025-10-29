<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Rental Ghana - Car Rental Made Simple' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">QR</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Rental Ghana</span>
                    </a>
                </div>

                <div class="flex items-center space-x-8">
                    <a href="{{ route('vehicles.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Vehicles</a>
                    <a href="{{ route('rental.properties') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Rent a Home</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors">My Bookings</a>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-blue-600 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Sign Up
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="lg:hidden">
                @if(request()->routeIs('home') || request()->routeIs('vehicles.index'))
                    <!-- Breadcrumb Navigation for Home and Vehicles -->
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                            </a>
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @if(request()->routeIs('home'))
                                <span class="text-gray-600 font-medium">Home</span>
                            @elseif(request()->routeIs('vehicles.index'))
                                <span class="text-gray-600 font-medium">Vehicles</span>
                            @endif
                        </div>

                        <!-- Mobile User Menu -->
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">{{ auth()->user()->name }}</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition-colors text-sm">
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors text-sm">Login</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                    Sign Up
                                </a>
                            @endauth
                        </div>
                    </div>
                @else
                    <!-- Mobile Menu with Toggle -->
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">QR</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">Rental Ghana</span>
                            </a>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Menu Dropdown -->
                    <div id="mobile-menu" class="hidden bg-white border-t border-gray-200">
                        <div class="px-2 pt-2 pb-3 space-y-1">
                            <a href="{{ route('vehicles.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Vehicles</a>
                            <a href="{{ route('rental.properties') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Rent a Home</a>
                            
                            @auth
                                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">My Bookings</a>
                                <div class="border-t border-gray-200 pt-4 pb-3">
                                    <div class="flex items-center px-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                            <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-3 px-2 space-y-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="border-t border-gray-200 pt-4 pb-3">
                                    <div class="px-2 space-y-1">
                                        <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md">Login</a>
                                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium bg-blue-600 text-white hover:bg-blue-700 rounded-md">Sign Up</a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">QR</span>
                        </div>
                        <span class="text-xl font-bold">Rental Ghana</span>
                    </div>
                    <p class="text-gray-400">Your trusted partner for reliable car rentals.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Services</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Economy Cars</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Luxury Vehicles</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">SUVs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Vans</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>📞 +1 (555) 123-4567</li>
                        <li>✉️ info@quickrental.com</li>
                        <li>📍 123 Main St, City, State 12345</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Rental Ghana. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
