/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.php",
    "./public/**/*.html",
    "./public/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1b60cc', // Brand Accent Blue (from logo)
        secondary: '#111e2e', // Deep Navy (from logo text)
        darkbg: '#111827',
        darkcard: '#1f2937',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      }
    },
  },
  plugins: [],
}

