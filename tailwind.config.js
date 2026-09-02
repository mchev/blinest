import defaultTheme from 'tailwindcss/defaultTheme';

const withAlpha = (variable) => `rgb(var(${variable}) / <alpha-value>)`;

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', ...defaultTheme.fontFamily.sans],
      },
      borderRadius: {
        squircle: '1.5rem',
        'squircle-sm': '1.25rem',
        'squircle-xs': '1rem',
      },
      colors: {
        brand: {
          midnight: withAlpha('--color-midnight'),
          deep: withAlpha('--color-deep'),
          'deep-hover': withAlpha('--color-deep-hover'),
          'deep-card': withAlpha('--color-deep-card'),
          primary: withAlpha('--color-primary'),
          'primary-hover': withAlpha('--color-primary-hover'),
          'primary-dark': withAlpha('--color-primary-dark'),
          'primary-light': withAlpha('--color-primary-light'),
          secondary: withAlpha('--color-secondary'),
          'secondary-dark': withAlpha('--color-secondary-dark'),
          'secondary-strong': withAlpha('--color-secondary-strong'),
          accent: withAlpha('--color-accent'),
          'accent-hover': withAlpha('--color-accent-hover'),
          'accent-dark': withAlpha('--color-accent-dark'),
          'accent-light': withAlpha('--color-accent-light'),
        },
        red: {
          DEFAULT: '#307C82',
          50: '#eef8f9',
          100: '#d6eef0',
          200: '#aedde1',
          300: '#7ec5cb',
          400: withAlpha('--color-primary-light'),
          500: withAlpha('--color-primary'),
          600: withAlpha('--color-primary-dark'),
          700: '#20646A',
          800: '#184f54',
          900: '#103538',
          950: '#0a2225',
        },
        teal: {
          DEFAULT: '#00ADB5',
          400: withAlpha('--color-accent-hover'),
          500: withAlpha('--color-accent'),
          600: withAlpha('--color-accent-dark'),
          700: '#006a70',
          800: '#004548',
          900: '#002a2d',
          950: '#001a1c',
        },
        yellow: {
          DEFAULT: '#F9ED69',
          400: withAlpha('--color-secondary'),
          500: withAlpha('--color-secondary-dark'),
          600: '#b8a832',
        },
        orange: {
          400: withAlpha('--color-primary-light'),
          500: withAlpha('--color-primary'),
          600: withAlpha('--color-primary-dark'),
          700: '#20646A',
          950: '#0a2225',
        },
        sky: {
          300: withAlpha('--color-accent-hover'),
          400: withAlpha('--color-accent-hover'),
          500: withAlpha('--color-accent'),
          600: withAlpha('--color-accent-dark'),
        },
        emerald: {
          300: withAlpha('--color-accent-hover'),
          400: withAlpha('--color-accent-hover'),
          500: withAlpha('--color-accent'),
          600: withAlpha('--color-accent-dark'),
        },
        indigo: {
          400: withAlpha('--color-accent-hover'),
          500: withAlpha('--color-accent'),
          600: withAlpha('--color-accent-dark'),
          700: '#006a70',
          800: '#004548',
          900: withAlpha('--color-deep'),
        },
        surface: {
          DEFAULT: withAlpha('--color-deep-card'),
          base: withAlpha('--color-midnight'),
          raised: withAlpha('--color-deep'),
          overlay: withAlpha('--color-deep-card'),
          hover: withAlpha('--color-deep-hover'),
        },
        arena: {
          base: withAlpha('--color-midnight'),
          panel: withAlpha('--color-deep'),
          card: withAlpha('--color-deep-card'),
          hover: withAlpha('--color-deep-hover'),
        },
        live: withAlpha('--color-primary'),
        twitch: withAlpha('--color-accent'),
        shark: {
          DEFAULT: '#3a4548',
          50: '#E8ECEC',
          100: '#D5DBDC',
          200: '#B0BABB',
          300: '#8B989A',
          400: '#6B7779',
          500: '#525C5E',
          600: '#434B4D',
          700: '#3A4548',
          800: '#2A3234',
          900: '#1E2426',
          950: '#141819',
        },
      },
      keyframes: {
        shaking: {
          '0%': { transform: 'translate(0, 0) rotate(0deg)' },
          '25%': { transform: 'translate(2px, 2px) rotate(0.1deg)' },
          '50%': { transform: 'translate(0, 0) rotate(0eg)' },
          '75%': { transform: 'translate(-2px, 2px) rotate(-0.1deg)' },
          '100%': { transform: 'translate(0, 0) rotate(0deg)' },
        },
      },
      animation: {
        shake: 'shaking .2s cubic-bezier(.36,.07,.19,.97) infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
};
