import './bootstrap'
import '@keenthemes/ktui/dist/ktui.min.js'
import 'preline'

document.addEventListener('DOMContentLoaded', () => {
  if (window.HSStaticMethods) {
    window.HSStaticMethods.autoInit()
  }
})
