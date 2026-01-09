import './bootstrap'
import '@keenthemes/ktui/dist/ktui.min.js'
import 'preline'
import ApexCharts from 'apexcharts'

window.ApexCharts = ApexCharts

document.addEventListener('DOMContentLoaded', () => {
  if (window.HSStaticMethods) {
    window.HSStaticMethods.autoInit()
  }
})
