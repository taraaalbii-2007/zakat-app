<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Niat Zakat') }} - @yield('title')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* ─── Base ─────────────────────────────────────────── */
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem; /* 14px — sesuai ukuran sidebar */
            color: #374151;
            background-color: #f3f4f6;
        }

        /* ─── Toast ─────────────────────────────────────────── */
        .toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 99998;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
        }

        .toast {
            pointer-events: auto;
            min-width: 260px;
            max-width: 340px;
            padding: 0.75rem 0.875rem;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 16px -2px rgba(0,0,0,0.08), 0 2px 6px -1px rgba(0,0,0,0.06);
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            animation: slideInRight 0.25s ease-out;
            border: 1px solid #f3f4f6;
        }

        .toast.toast-exit {
            animation: slideOutRight 0.25s ease-in forwards;
        }

        @keyframes slideInRight {
            from { transform: translateX(360px); opacity: 0; }
            to   { transform: translateX(0);     opacity: 1; }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0);     opacity: 1; }
            to   { transform: translateX(360px); opacity: 0; }
        }

        .toast-icon {
            flex-shrink: 0;
            width: 1.125rem;
            height: 1.125rem;
            margin-top: 0.05rem;
        }

        .toast-content {
            flex: 1;
            font-size: 0.8125rem;
            line-height: 1.45;
            color: #4b5563;
        }

        .toast-close {
            flex-shrink: 0;
            width: 1rem;
            height: 1rem;
            cursor: pointer;
            opacity: 0.4;
            transition: opacity 0.15s;
            color: #6b7280;
        }

        .toast-close:hover { opacity: 0.8; }

        .toast-success { border-left: 3px solid #10b981; }
        .toast-error   { border-left: 3px solid #ef4444; }
        .toast-warning { border-left: 3px solid #f59e0b; }
        .toast-info    { border-left: 3px solid #3b82f6; }

        .toast-success .toast-icon { color: #10b981; }
        .toast-error   .toast-icon { color: #ef4444; }
        .toast-warning .toast-icon { color: #f59e0b; }
        .toast-info    .toast-icon { color: #3b82f6; }

        .toast-content-message {
            color: #374151;
            font-size: 0.8125rem;
        }

        @media (max-width: 768px) {
            .toast-container { right: 0.75rem; left: 0.75rem; top: 0.75rem; }
            .toast { min-width: auto; width: 100%; }
        }

        /* ─── Loading overlay ───────────────────────────────── */
        #loading-overlay {
            backdrop-filter: blur(4px);
        }

        /* ─── Scroll to top btn ─────────────────────────────── */
        #scroll-top-btn {
            background-color: #2d6a2d;
            color: #fff;
        }
        #scroll-top-btn:hover {
            background-color: #245724;
        }

        /* ─── Fade-in animation for page content ────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0);    }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.25s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('partials.splash-screen')
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        @include('partials.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64 relative">
            <!-- Navbar -->
            @include('partials.navbar')
            
            <!-- Page Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumbs -->
                <div class="mb-5">
                    @include('partials.breadcrumbs')
                </div>
                
                <!-- Main Content -->
                <div class="animate-fade-in-up">
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            @include('partials.footer')
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 lg:hidden hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-white/75 z-50 hidden items-center justify-center">
        <div class="text-center">
            <div class="w-10 h-10 border-4 border-gray-200 border-t-[#2d6a2d] rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-gray-500 text-sm font-medium">Memuat...</p>
        </div>
    </div>
    
    <script>
        // ── Toast Notification System ───────────────────────────
        const ToastNotification = {
            container: null,

            init() {
                this.container = document.getElementById('toastContainer');
            },

            show(message, type = 'success', duration = 3500) {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;

                const icons = {
                    success: `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`,
                    error:   `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`,
                    warning: `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`,
                    info:    `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>`,
                };

                toast.innerHTML = `
                    ${icons[type] || icons.info}
                    <div class="toast-content">
                        <div class="toast-content-message">${message}</div>
                    </div>
                    <svg class="toast-close" fill="currentColor" viewBox="0 0 20 20" onclick="this.parentElement.remove()">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                `;

                this.container.appendChild(toast);
                setTimeout(() => this.hide(toast), duration);
            },

            hide(toast) {
                toast.classList.add('toast-exit');
                setTimeout(() => { if (toast.parentElement) toast.remove(); }, 250);
            }
        };

        // ── Sidebar toggle ──────────────────────────────────────
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // ── Loading helpers ─────────────────────────────────────
        function showLoading() {
            const el = document.getElementById('loading-overlay');
            el.classList.remove('hidden');
            el.classList.add('flex');
        }
        function hideLoading() {
            const el = document.getElementById('loading-overlay');
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        // ── Scroll to top ───────────────────────────────────────
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ── Init ────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            ToastNotification.init();
            window.ToastNotification = ToastNotification;

            // Flash messages dari Laravel session
            @if(session('success'))
                ToastNotification.show("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                ToastNotification.show("{{ session('error') }}", 'error');
            @endif
            @if(session('warning'))
                ToastNotification.show("{{ session('warning') }}", 'warning');
            @endif
            @if(session('info'))
                ToastNotification.show("{{ session('info') }}", 'info');
            @endif

            // Show/hide scroll-to-top button
            window.addEventListener('scroll', function () {
                const btn = document.getElementById('scroll-top-btn');
                if (btn) {
                    btn.classList.toggle('hidden', window.pageYOffset <= 300);
                }
            });
        });
    </script>
    
    <!-- Scroll to Top Button -->
    <button id="scroll-top-btn"
            onclick="scrollToTop()"
            class="hidden fixed bottom-6 right-6 z-50 w-10 h-10 rounded-full shadow-md hover:shadow-lg transition-all duration-200 hover:scale-105 flex items-center justify-center"
            title="Kembali ke atas">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>
    
    @stack('scripts')
</body>
</html>