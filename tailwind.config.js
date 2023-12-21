const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
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
      colors: {
        'red': {  DEFAULT: '#DD5E5E',  50: '#FDF6F6',  100: '#F9E5E5',  200: '#F2C3C3',  300: '#EBA1A1',  400: '#E48080',  500: '#DD5E5E',  600: '#D33030',  700: '#A72323',  800: '#791A1A',  900: '#4B1010',  950: '#340B0B'},
        'shark': {  DEFAULT: '#272B2C',  50: '#A9B1B3',  100: '#9EA7A9',  200: '#899496',  300: '#748083',  400: '#616A6D',  500: '#4D5557',  600: '#3A4042',  700: '#272B2C',  800: '#0D0E0E',  900: '#000000',  950: '#000000'},
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
