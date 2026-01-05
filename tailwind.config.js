import forms from '@tailwindcss/forms'
import preline from 'preline/plugin'

export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './node_modules/preline/dist/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    forms,
    preline,
  ],
}
