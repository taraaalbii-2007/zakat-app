<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Auth') - Niat Zakat</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Animated background */
        .animated-bg {
            background: linear-gradient(135deg, #2d6936 0%, #1e5223 50%, #7cb342 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Floating animation for decorative elements */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Glass morphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        /* Pulse animation for decorative circles */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }
        
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Additional style for complete profile */
        .complete-profile-layout .main-container {
            max-width: none !important;
            width: 100% !important;
            align-items: flex-start !important;
        }
        
        .complete-profile-layout .content-container {
            max-width: none !important;
            width: 100% !important;
        }

        /* Toast Notification Styles */
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

        .toast-success .toast-icon {
            color: #10b981;
        }

        .toast-error .toast-icon {
            color: #ef4444;
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

        /* ReCaptcha Badge Position - TIDAK KETINDIHAN */
        .recaptcha-badge {
            position: fixed !important;
            bottom: 25px !important;
            right: 25px !important;
            z-index: 100000 !important;
            background: rgba(0, 0, 0, 0.8) !important;
            color: white !important;
            padding: 6px 12px !important;
            border-radius: 6px !important;
            font-size: 11px !important;
            font-family: 'Poppins', sans-serif !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            pointer-events: none !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        }

        /* Pastikan semua elemen lain z-index lebih rendah */
        .auth-card {
            z-index: 10;
            position: relative;
        }

        .content-container {
            z-index: 20;
            position: relative;
        }

        /* Dekoratif background HARUS di belakang */
        .absolute.inset-0.overflow-hidden {
            z-index: 1 !important;
        }

        /* Responsive untuk ReCaptcha di mobile */
        @media (max-width: 768px) {
            .recaptcha-badge {
                bottom: 70px !important;
                right: 15px !important;
                font-size: 10px !important;
                padding: 5px 10px !important;
            }
            
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
        
        /* Untuk halaman lengkapi profil yang lebar */
        @media (min-width: 1400px) {
            .complete-profile-layout .content-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="min-h-screen animated-bg overflow-x-hidden {{ request()->routeIs('complete-profile.*') ? 'complete-profile-layout' : '' }}">

    @include('partials.splash-screen')
    
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        
        @if(!request()->routeIs('complete-profile.*'))
        <!-- Decorative Background Elements (Hanya untuk halaman auth biasa) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none" style="z-index: 1;">
            <!-- Top Left Circle -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary-400 rounded-full opacity-20 blur-3xl"></div>
            
            <!-- Top Right Circle -->
            <div class="absolute -top-10 -right-10 w-96 h-96 bg-secondary-400 rounded-full opacity-15 blur-3xl float-animation"></div>
            
            <!-- Bottom Left Circle -->
            <div class="absolute -bottom-20 -left-10 w-80 h-80 bg-accent-400 rounded-full opacity-10 blur-3xl" style="animation-delay: 2s;"></div>
            
            <!-- Bottom Right Circle -->
            <div class="absolute -bottom-32 -right-20 w-72 h-72 bg-primary-300 rounded-full opacity-20 blur-3xl float-animation" style="animation-delay: 4s;"></div>
            
            <!-- Floating Islamic Pattern -->
            <div class="absolute top-1/4 right-1/4 opacity-5">
                <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" stroke="white" stroke-width="2"/>
                    <circle cx="100" cy="100" r="60" stroke="white" stroke-width="2"/>
                    <circle cx="100" cy="100" r="40" stroke="white" stroke-width="2"/>
                    <circle cx="100" cy="100" r="20" stroke="white" stroke-width="2"/>
                </svg>
            </div>
        </div>
        
        @endif
        
        <!-- Content Container -->
        <div class="w-full {{ request()->routeIs('complete-profile.*') ? 'max-w-full px-0' : 'max-w-md relative z-10' }}" style="z-index: 20;">
            
            @if(request()->routeIs('complete-profile.*'))
            <!-- Header khusus untuk halaman lengkapi profil -->
            <div class="bg-white shadow-sm mb-8 py-6 px-8">
                <div class="max-w-6xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-primary-100 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
                                <circle cx="12" cy="14" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Niat Zakat</h1>
                            <p class="text-gray-600 text-sm">Platform Pengelolaan Zakat Digital</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-bold text-gray-800">Lengkapi Profil</h2>
                        <p class="text-gray-600 text-sm">Selesaikan pendaftaran Anda</p>
                    </div>
                </div>
            </div>
            @else
            <!-- Auth Card (Hanya untuk halaman auth biasa) -->
            <div class="glass-effect rounded-3xl shadow-nz-xl p-8 sm:p-10 animate-scale-in" style="z-index: 10;">
                
                <!-- Card Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-neutral-800 mb-2">
                        @yield('card-title', 'Welcome Back')
                    </h2>
                    <p class="text-neutral-600 text-sm">
                        @yield('card-subtitle', 'Silakan login untuk melanjutkan')
                    </p>
                </div>
                
                <!-- Main Content -->
                @yield('content')
                
            </div>
            @endif
            
            <!-- Main Content untuk halaman lengkapi profil -->
            @if(request()->routeIs('complete-profile.*'))
                @yield('content')
            @endif
            
            <!-- Footer Links -->
            <div class="mt-6 text-center animate-fade-in" style="z-index: 20;">
                @yield('footer-links')
            </div>
            
            <!-- Additional Info -->
            <div class="mt-8 text-center" style="z-index: 20;">
                <p class="text-primary-100 text-xs">
                    &copy; {{ date('Y') }} Niat Zakat. All rights reserved.
                </p>
            </div>
            
        </div>
        
    </div>
    
    <!-- Scripts -->
    <script>
        // Toast Notification System
        const ToastNotification = {
            container: null,
            
            init() {
                this.container = document.getElementById('toastContainer');
            },
            
            show(message, type = 'success', duration = 3000) {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                const icon = type === 'success' 
                    ? `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                       </svg>`
                    : `<svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                       </svg>`;
                
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
            
            @if($errors->any())
                @foreach($errors->all() as $error)
                    ToastNotification.show("{{ $error }}", 'error');
                @endforeach
            @endif
            
            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
            
            // Add input focus animations
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"], input[type="tel"]');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-primary-300');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-primary-300');
                });
            });
        });
    </script>
    
    @stack('scripts')

</body>
</html>