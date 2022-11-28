/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      minHeight: {
        '250': '250px',
      },
    },
  },
  plugins: [],
}
