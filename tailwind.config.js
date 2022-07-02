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
        blinest: {
          100: '#e6e8ff',
          300: '#b2b7ff',
          400: '#7886d7',
          500: '#6574cd',
          600: '#5661b3',
          800: '#2f365f',
          900: '#191e38',
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
        'shake': 'shaking .2s cubic-bezier(.36,.07,.19,.97) infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
