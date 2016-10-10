import ga from '../components/ga'

export default function (to, from) {
  ga('send', 'pageview')

  document.title = to.meta.title || 'CCU Plus | 全新生活 由此領航'
}
