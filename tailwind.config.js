/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Filament/**/*.php",
    "./database/factories/**/*.php",
    "./database/seeders/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        // Custom neon/cyberpunk colors
        primary: {
          50: '#f0fdff',
          100: '#ccfbfe',
          200: '#99f5fe',
          300: '#66eefc',
          400: '#33e4fa',
          500: '#00ffff', // Main cyan color
          600: '#00d4e6',
          700: '#00b8cc',
          800: '#009ab3',
          900: '#007d91',
          950: '#00505e',
        },
        secondary: {
          50: '#fdf4ff',
          100: '#fae8ff',
          200: '#f5d0fe',
          300: '#f0abfc',
          400: '#e879f9',
          500: '#ff00ff', // Main magenta color
          600: '#d946ef',
          700: '#c026d3',
          800: '#a21caf',
          900: '#86198f',
          950: '#581c87',
        },
        accent: {
          50: '#fefce8',
          100: '#fef9c3',
          200: '#fef08a',
          300: '#fde047',
          400: '#facc15',
          500: '#ffff00', // Main yellow color
          600: '#eab308',
          700: '#ca8a04',
          800: '#a16207',
          900: '#854d0e',
          950: '#713f12',
        },
        // Dark theme colors
        'dark-bg': {
          primary: '#0a0a0a',
          secondary: '#1a1a2e',
          tertiary: '#16213e',
          card: 'rgba(255, 255, 255, 0.05)',
          hover: 'rgba(255, 255, 255, 0.1)',
        },
        'dark-text': {
          primary: '#ffffff',
          secondary: 'rgba(255, 255, 255, 0.8)',
          muted: 'rgba(255, 255, 255, 0.6)',
          disabled: 'rgba(255, 255, 255, 0.4)',
        },
        'dark-border': {
          primary: 'rgba(255, 255, 255, 0.1)',
          secondary: 'rgba(255, 255, 255, 0.2)',
          accent: 'rgba(0, 255, 255, 0.3)',
        }
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(45deg, #00ffff, #ff00ff)',
        'gradient-secondary': 'linear-gradient(135deg, #1a1a2e, #16213e)',
        'gradient-accent': 'linear-gradient(90deg, #00ffff, #ffff00, #ff00ff)',
      },
      boxShadow: {
        'neon-primary': '0 0 20px rgba(0, 255, 255, 0.4), 0 0 40px rgba(0, 255, 255, 0.4), 0 0 60px rgba(0, 255, 255, 0.4)',
        'neon-secondary': '0 0 20px rgba(255, 0, 255, 0.3), 0 0 40px rgba(255, 0, 255, 0.3)',
        'neon-accent': '0 0 20px rgba(255, 255, 0, 0.3), 0 0 40px rgba(255, 255, 0, 0.3)',
      },
      animation: {
        'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate',
        'gradient-shift': 'gradient-shift 3s ease infinite',
      },
      keyframes: {
        'pulse-glow': {
          from: { filter: 'brightness(1)' },
          to: { filter: 'brightness(1.2)' },
        },
        'gradient-shift': {
          '0%, 100%': { filter: 'hue-rotate(0deg)' },
          '50%': { filter: 'hue-rotate(30deg)' },
        },
      },
      transitionTimingFunction: {
        'fast': '0.15s ease',
        'normal': '0.3s ease',
        'slow': '0.5s ease',
      },
      backdropBlur: {
        xs: '2px',
      },
    },
  },
  plugins: [],
}