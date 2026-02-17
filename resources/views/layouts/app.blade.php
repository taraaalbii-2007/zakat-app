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
    
    <!-- Toast Notification Styles -->
    <style>
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 99998;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast {
            pointer-events: auto;
            min-width: 280px;
            max-width: 350px;
            padding: 0.875rem 1rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideInRight 0.3s ease-out;
        }

        .toast.toast-exit {
            animation: slideOutRight 0.3s ease-in forwards;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-icon {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
        }

        .toast-content {
            flex: 1;
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .toast-close {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .toast-close:hover {
            opacity: 1;
        }

        .toast-success {
            border-left: 4px solid #10b981;
        }

        .toast-error {
            border-left: 4px solid #ef4444;
        }

        .toast-warning {
            border-left: 4px solid #f59e0b;
        }

        .toast-info {
            border-left: 4px solid #3b82f6;
        }

        .toast-success .toast-icon {
            color: #10b981;
        }

        .toast-error .toast-icon {
            color: #ef4444;
        }

        .toast-warning .toast-icon {
            color: #f59e0b;
        }

        .toast-info .toast-icon {
            color: #3b82f6;
        }

        .toast-content-title {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.125rem;
        }

        .toast-content-message {
            color: #6b7280;
            font-size: 0.8125rem;
        }

        @media (max-width: 768px) {
            .toast-container {
                right: 1rem;
                left: 1rem;
                top: 1rem;
            }

            .toast {
                min-width: auto;
                width: 100%;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gradient-to-br from-neutral-50 via-primary-50/30 to-secondary-50/20">
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="min-h-screen flex relative">
        <!-- Decorative Background Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-nz opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-32 w-80 h-80 bg-gradient-secondary opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 right-1/4 w-96 h-96 bg-gradient-primary opacity-5 rounded-full blur-3xl"></div>
        </div>

        <!-- Sidebar -->
        @include('partials.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64 relative z-10">
            <!-- Navbar -->
            @include('partials.navbar')
            
            <!-- Page Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumbs -->
                <div class="mb-6">
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
    <div id="sidebar-overlay" class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-100 lg:hidden hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>
    
    <!-- Loading Overlay (Optional) -->
    <div id="loading-overlay" class="fixed inset-0 bg-white/80 backdrop-blur-sm z-120 hidden items-center justify-center">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-neutral-600 font-medium">Memuat...</p>
        </div>
    </div>
    
    <script>
        // Toast Notification System (Sama persis dengan auth layout)
        const ToastNotification = {
            container: null,
            
            init() {
                this.container = document.getElementById('toastContainer');
            },
            
            show(message, type = 'success', duration = 3000) {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                let icon;
                switch(type) {
                    case 'success':
                        icon = `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                               </svg>`;
                        break;
                    case 'error':
                        icon = `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                               </svg>`;
                        break;
                    case 'warning':
                        icon = `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                               </svg>`;
                        break;
                    case 'info':
                        icon = `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                               </svg>`;
                        break;
                }
                
                toast.innerHTML = `
                    ${icon}
                    <div class="toast-content">
                        <div class="toast-content-message">${message}</div>
                    </div>
                    <svg class="toast-close" fill="currentColor" viewBox="0 0 20 20" onclick="this.parentElement.remove()">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                `;
                
                this.container.appendChild(toast);
                
                // Auto remove after duration
                setTimeout(() => {
                    this.hide(toast);
                }, duration);
            },
            
            hide(toast) {
                toast.classList.add('toast-exit');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }
        };
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            ToastNotification.init();
            
            // Show notifications from Laravel session
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
            
            // Toggle Sidebar
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
                
                // Add animation
                if (!overlay.classList.contains('hidden')) {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                }
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('[class*="animate-slide-down"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'all 0.3s ease-out';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Global loading function
            function showLoading() {
                const overlay = document.getElementById('loading-overlay');
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }

            function hideLoading() {
                const overlay = document.getElementById('loading-overlay');
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }

            // Smooth scroll to top
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Add scroll to top button
            window.addEventListener('scroll', function() {
                const scrollBtn = document.getElementById('scroll-top-btn');
                if (scrollBtn) {
                    if (window.pageYOffset > 300) {
                        scrollBtn.classList.remove('hidden');
                    } else {
                        scrollBtn.classList.add('hidden');
                    }
                }
            });
            
            // Expose ToastNotification globally
            window.ToastNotification = ToastNotification;
        });
    </script>
    
    <!-- Scroll to Top Button -->
    <button id="scroll-top-btn" onclick="scrollToTop()" class="hidden fixed bottom-8 right-8 z-110 w-12 h-12 bg-gradient-nz text-white rounded-full shadow-nz-lg hover:shadow-nz-xl transition-all duration-300 hover:scale-110 group">
        <svg class="w-6 h-6 mx-auto transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>
    
    @stack('scripts')
</body>
</html>