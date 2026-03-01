/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Primary - Hijau Niat Zakat (diupdate sesuai warna button)
        primary: {
          DEFAULT: '#17a34a', // ← Diupdate: hijau cerah dari button
          50:  '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#17a34a', // ← DEFAULT sama dengan 600
          700: '#15803d',
          800: '#166534',
          900: '#14532d',
          950: '#052e16',
        },
        // Secondary - Hijau tua (dulu primary)
        secondary: {
          DEFAULT: '#2d6936',
          50: '#f1f8e9',
          100: '#dcedc8',
          200: '#c5e1a5',
          300: '#aed581',
          400: '#8bc34a',
          500: '#2d6936',
          600: '#27612e',
          700: '#1e5223',
          800: '#174119',
          900: '#0f2f12',
          950: '#0a1f0c',
        },
        // Accent - Untuk CTA dan highlight
        accent: {
          DEFAULT: '#4caf50',
          50: '#e8f5e9',
          100: '#c8e6c9',
          200: '#a5d6a7',
          300: '#81c784',
          400: '#66bb6a',
          500: '#4caf50',
          600: '#43a047',
          700: '#388e3c',
          800: '#2e7d32',
          900: '#1b5e20',
          950: '#0d3d10',
        },
        // Neutral - Untuk teks dan background
        neutral: {
          50: '#fafafa',
          100: '#f5f5f5',
          200: '#eeeeee',
          300: '#e0e0e0',
          400: '#bdbdbd',
          500: '#9e9e9e',
          600: '#757575',
          700: '#616161',
          800: '#424242',
          900: '#212121',
        },
        // Warna tambahan untuk status
        success: {
          DEFAULT: '#4caf50',
          light: '#81c784',
          dark: '#388e3c',
        },
        warning: {
          DEFAULT: '#ff9800',
          light: '#ffb74d',
          dark: '#f57c00',
        },
        danger: {
          DEFAULT: '#f44336',
          light: '#e57373',
          dark: '#d32f2f',
        },
        info: {
          DEFAULT: '#2196f3',
          light: '#64b5f6',
          dark: '#1976d2',
        },
        // Surface colors
        surface: {
          DEFAULT: '#ffffff',
          50: '#ffffff',
          100: '#fafafa',
          200: '#f5f5f5',
          300: '#f0f0f0',
        },
      },
      fontFamily: {
        sans: ['Poppins', 'system-ui', '-apple-system', 'sans-serif'],
        heading: ['Poppins', 'sans-serif'],
        display: ['Poppins', 'sans-serif'],
        body: ['Poppins', 'sans-serif'],
      },
      boxShadow: {
        'nz': '0 4px 6px -1px rgba(23, 163, 74, 0.1), 0 2px 4px -1px rgba(23, 163, 74, 0.06)',
        'nz-lg': '0 10px 15px -3px rgba(23, 163, 74, 0.15), 0 4px 6px -2px rgba(23, 163, 74, 0.1)',
        'nz-xl': '0 20px 25px -5px rgba(23, 163, 74, 0.2), 0 10px 10px -5px rgba(23, 163, 74, 0.1)',
        'card': '0 4px 14px 0 rgba(23, 163, 74, 0.08)',
        'card-hover': '0 8px 25px 0 rgba(23, 163, 74, 0.15)',
        'soft': '0 2px 8px 0 rgba(0, 0, 0, 0.06)',
        'soft-lg': '0 4px 16px 0 rgba(0, 0, 0, 0.08)',
        'modal': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, #17a34a 0%, #15803d 100%)',
        'gradient-secondary': 'linear-gradient(135deg, #2d6936 0%, #1e5223 100%)',
        'gradient-nz': 'linear-gradient(135deg, #17a34a 0%, #2d6936 100%)',
        'gradient-nz-radial': 'radial-gradient(circle at top right, #17a34a, #2d6936)',
        'gradient-light': 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
        'gradient-white': 'linear-gradient(180deg, #ffffff 0%, #fafafa 100%)',
        'gradient-header': 'linear-gradient(90deg, #17a34a 0%, #22c55e 100%)',
        'gradient-subtle': 'linear-gradient(180deg, rgba(23, 163, 74, 0.03) 0%, transparent 100%)',
      },
      zIndex: {
        '100': '100',
        '110': '110',
        '120': '120',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-out',
        'fade-in-up': 'fadeInUp 0.4s ease-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'slide-down': 'slideDown 0.3s ease-out',
        'slide-in-right': 'slideInRight 0.3s ease-out',
        'slide-in-left': 'slideInLeft 0.3s ease-out',
        'scale-in': 'scaleIn 0.2s ease-out',
        'bounce-slow': 'bounce 2s infinite',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideInRight: {
          '0%': { transform: 'translateX(100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        slideInLeft: {
          '0%': { transform: 'translateX(-100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem',
      },
      backgroundColor: {
        'page': '#fafafa',
        'card': '#ffffff',
        'muted': '#f5f5f5',
      },
    },
  },
  plugins: [],
}