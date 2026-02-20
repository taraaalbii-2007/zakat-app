<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Zakat Masjid</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
     @include('partials.splash-screen')
    {{-- Header --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center">
                            <span class="text-white font-bold">Z</span>
                        </div>
                        <span class="ml-3 text-lg font-semibold text-gray-900">Zakat Masjid</span>
                    </a>
                </div>
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-primary transition-colors">Beranda</a>
                    <a href="{{ route('laporan-keuangan.public.index') }}" class="text-primary font-medium">Laporan Keuangan</a>
                </nav>
                <div class="md:hidden">
                    {{-- Mobile menu button --}}
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Sistem Manajemen Zakat Masjid. Semua hak dilindungi.</p>
                <p class="mt-2 text-gray-400 text-sm">Transparansi dan Akuntabilitas Pengelolaan Zakat</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>