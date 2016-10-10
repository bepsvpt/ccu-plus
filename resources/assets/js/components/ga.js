const push = () => {
  window.ga.q = window.ga.q || []

  window.ga.q.push(arguments)
}

window.ga = window.ga || push

window.ga.l = 1 * new Date()

const el = document.createElement('script')

el.async = 1
el.src = 'https://www.google-analytics.com/analytics.js'

document.getElementsByTagName('head')[0].appendChild(el)

window.ga('create', 'UA-65962475-4', 'auto')

export default window.ga
