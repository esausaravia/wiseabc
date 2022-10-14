/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.{php,html}"
  ],
  theme: {
    fontFamily:{
      'sans': ['"Open Sans"', defaultTheme.fontFamily.sans],
      'accent': ['Montserrat', defaultTheme.fontFamily.sans]
    },
    extend: {
      blur: {
        px: '1px'
      },
      colors:{
        'azul':'#0B1F41',
        'rojo':'#BE1F41'
      },
      container: {
        screens: {
          xl: '1200px',
          '2xl': '1200px'
        }
      },
      fontSize: {
        '40': '2.5rem'
      },
      lineHeight: {
        '12': '3rem'
      },
      spacing: {
        '10px': '0.625rem'
      }
    },
    container: {
      center: true
    }
  },
  plugins: [
    require('@tailwindcss/aspect-ratio'),
  ],
}
