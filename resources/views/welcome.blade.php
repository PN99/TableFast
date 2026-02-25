<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TableFast') }} | {{ __('ui.welcome.page_title') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(255, 255, 255, 0.4); }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-delay-1 { animation-delay: 0.1s; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-pattern { background-image: radial-gradient(rgba(245, 158, 11, 0.12) 1px, transparent 1px); background-size: 40px 40px; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 overflow-x-hidden selection:bg-amber-500 selection:text-white flex flex-col min-h-screen">

    {{-- Navigation --}}
    <header class="fixed w-full z-50 glass-nav shadow-[0_4px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 group">
                    <div class="bg-amber-500 rounded-xl p-2 text-white shadow-lg shadow-amber-500/30 group-hover:rotate-12 group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                        </svg>
                    </div>
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-gray-900 group-hover:text-amber-600 transition-colors">
                        Table<span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Fast</span>
                    </span>
                </a>

                {{-- Quick actions --}}
                <nav class="flex items-center gap-3 sm:gap-5">
                    {{-- Language Switcher --}}
                    <div class="flex items-center gap-1.5 text-sm font-semibold">
                        <a href="{{ route('locale.set', 'cs') }}"
                           class="{{ app()->getLocale() === 'cs' ? 'text-amber-600 font-bold' : 'text-gray-400 hover:text-gray-600 transition-colors' }}">🇨🇿</a>
                        <span class="text-gray-200 text-xs">|</span>
                        <a href="{{ route('locale.set', 'en') }}"
                           class="{{ app()->getLocale() === 'en' ? 'text-amber-600 font-bold' : 'text-gray-400 hover:text-gray-600 transition-colors' }}">🇬🇧</a>
                    </div>
                    @auth
                        <div class="hidden sm:flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm border-2 border-amber-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('dashboard') }}" wire:navigate class="text-sm font-bold text-gray-600 hover:text-amber-600 transition-colors hidden sm:block">
                            {{ __('ui.nav.my_reservations') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">
                                {{ __('ui.nav.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors hidden sm:block">
                            {{ __('ui.nav.login') }}
                        </a>
                        <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-amber-500 hover:shadow-lg hover:shadow-amber-500/30 hover:-translate-y-0.5 transition-all duration-300">
                            {{ __('ui.nav.register') }}
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="relative pt-24 pb-12 overflow-hidden flex-grow">
        <div class="absolute inset-0 hero-pattern pointer-events-none opacity-70"></div>
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/4 w-[700px] h-[700px] bg-amber-300/25 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/4 w-[500px] h-[500px] bg-orange-300/15 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="animate-fade-in-up opacity-0 animate-delay-1">
                <livewire:reservation-wizard />
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-100 w-full py-6 relative z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 opacity-60 hover:opacity-100 transition-opacity">
                <div class="bg-gray-200 rounded-md p-1">
                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                    </svg>
                </div>
                <span class="text-base font-extrabold text-gray-900">Table<span class="text-amber-500">Fast</span></span>
            </div>
            <p class="text-xs font-medium text-gray-400">&copy; {{ date('Y') }} {{ __('ui.welcome.all_rights') }}</p>
        </div>
    </footer>

</body>
</html>
