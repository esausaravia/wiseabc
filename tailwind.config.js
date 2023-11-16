/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.{php,html}"
  ],
  theme: {
    extend: {
      blur: {
        px: '1px'
      },
      colors:{
        'rojo':'#BE1F41',
        'azulw':'#0B1F41',
        'azul':{
          50:"#c5dbfd",
          100:"#a6c8fb",
          200:"#8ab4f4",
          300:"#70a1ec",
          400:"#467cd1",
          500:"#2b5cab",
          600:"#1a4181",
          700:"#102d5c",
          800:"#0b1f41",
          900:"#031028",
          950:"#000919",
        }
      },
      container: {
        screens: {
          xl: '1200px',
          '2xl': '1200px'
        }
      },
      fontFamily:{
        'sans': ['"Open Sans"', defaultTheme.fontFamily.sans],
        'serif': ['"Roboto Slab"', defaultTheme.fontFamily.serif],
        'accent': ['Montserrat', defaultTheme.fontFamily.sans]
      },
      fontSize: {
        '40': '2.5rem'
      },
      lineHeight: {
        '22px': '1.375rem'
      },
      spacing: {
        '10px': '0.625rem'
      }/*,
      screens: {
        xl: '1200px'
      }*/
    },
    container: {
      center: true
    }
  },
  plugins: [
    require('@tailwindcss/aspect-ratio'),
  ],
}
