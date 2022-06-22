module.exports = {
  content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
  //darkMode: 'media', // or 'media' or 'class' or false
  theme: {
    extend: {
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
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
