import defaultTheme from 'tailwindcss/defaultTheme';

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
        'sans': ['Poppins', ...defaultTheme.fontFamily.sans],
      },
      borderRadius: {
        'squircle': '1.5rem',
        'squircle-sm': '1.25rem',
        'squircle-xs': '1rem',
      },
      colors: {
        'red': {  DEFAULT: '#DD5E5E',  50: '#FDF6F6',  100: '#F9E5E5',  200: '#F2C3C3',  300: '#EBA1A1',  400: '#E48080',  500: '#DD5E5E',  600: '#D33030',  700: '#A72323',  800: '#791A1A',  900: '#4B1010',  950: '#340B0B'},
        'surface': {
          DEFAULT: '#1f1f23',
          base: '#0e0e10',
          raised: '#18181b',
          overlay: '#1f1f23',
          hover: '#26262c',
        },
        'arena': {
          base: '#0e0e10',
          panel: '#18181b',
          card: '#1f1f23',
          hover: '#26262c',
        },
        'live': '#eb0400',
        'twitch': '#9146ff',
        'shark': {  DEFAULT: '#3a4548',  50: '#E8ECEC',  100: '#D5DBDC',  200: '#B0BABB',  300: '#8B989A',  400: '#6B7779',  500: '#525C5E',  600: '#434B4D',  700: '#3A4548',  800: '#2A3234',  900: '#1E2426',  950: '#141819'},
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
        'shake': 'shaking .2s cubic-bezier(.36,.07,.19,.97) infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
