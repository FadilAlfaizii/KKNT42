import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],
    theme: {
        extend: {
            colors: {
                // Natural Forest Green Theme - Homepage
                forest: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',  // Primary forest green
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                sage: {
                    50: '#f6f7f4',
                    100: '#e8eae0',
                    200: '#d4d8c5',
                    300: '#b5bb9d',
                    400: '#9ba57c',  // Sage green accent
                    500: '#7d8760',
                    600: '#636b4c',
                    700: '#4e543e',
                    800: '#414633',
                    900: '#373b2d',
                    950: '#1d1f18',
                },
                wood: {
                    50: '#faf7f2',
                    100: '#f2ebe0',
                    200: '#e5d4bf',
                    300: '#d4b896',  // Light wood texture
                    400: '#c19a6b',  // Medium wood
                    500: '#a67c52',
                    600: '#8b6642',
                    700: '#6f5138',
                    800: '#5c4330',
                    900: '#4d3929',
                    950: '#2a1f16',
                },
                // Modern Blue Theme - Dashboard
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
                accent: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
                // Hijau alami untuk dashboard
                nature: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                // Legacy support (keep for backward compatibility)
                green: '#16a34a',
                HoverGreen: '#15803d',
            },
            fontFamily: {
                sans: ['Inter', 'Manrope', 'sans-serif'],
                display: ['Cal Sans', 'Inter', 'sans-serif'],
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-in',
                'fade-in-up': 'fadeInUp 0.8s ease-out',
                'fade-in-down': 'fadeInDown 0.8s ease-out',
                'slide-in-left': 'slideInLeft 0.6s ease-out',
                'slide-in-right': 'slideInRight 0.6s ease-out',
                'bounce-slow': 'bounce 3s infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite alternate',
                'shimmer': 'shimmer 2s linear infinite',
                'gradient': 'gradient 8s ease infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { 
                        opacity: '0',
                        transform: 'translateY(30px)',
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                fadeInDown: {
                    '0%': { 
                        opacity: '0',
                        transform: 'translateY(-30px)',
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                slideInLeft: {
                    '0%': { 
                        opacity: '0',
                        transform: 'translateX(-50px)',
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
                slideInRight: {
                    '0%': { 
                        opacity: '0',
                        transform: 'translateX(50px)',
                    },
                    '100%': { 
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                glow: {
                    '0%': { boxShadow: '0 0 20px rgba(22, 163, 74, 0.5)' },
                    '100%': { boxShadow: '0 0 40px rgba(22, 163, 74, 0.8)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-1000px 0' },
                    '100%': { backgroundPosition: '1000px 0' },
                },
                gradient: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                'gradient-mesh': 'linear-gradient(135deg, #16a34a 0%, #15803d 100%)',
                'wood-texture': 'url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23c19a6b\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")',
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },
    plugins: [],
}
