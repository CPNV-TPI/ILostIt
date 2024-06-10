/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./View/**/*.php"],
  theme: {
    extend: {
      fontFamily: {
        istok: ['"Istok Web"', 'sans-serif'],
      },
      colors: {
        'primary': '#36D100',
        'secondary': '#C2FFAD',
        'tertiary': '#257C06'
      }
    },
  },
  plugins: [],
}

