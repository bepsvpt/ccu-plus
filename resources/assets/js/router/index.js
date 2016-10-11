import beforeEach from './beforeEach'
import afterEach from './afterEach'

const routes = [
  {
    path: '/',
    components: {
      default: require('../views/core.vue'),
      header: require('../views/layouts/header.vue'),
      footer: require('../views/layouts/footer.vue')
    },
    children: [
      { path: '', name: 'home', component: resolve => require(['../views/home.vue'], resolve) },
      { path: '*', name: 'not-found', component: resolve => require(['../views/not-found.vue'], resolve) }
    ]
  }
]

const scrollBehavior = (to, from, savedPosition) => {
  if (savedPosition) {
    return savedPosition
  } else if (to.hash) {
    return { selector: to.hash }
  }

  return { x: 0, y: 0 }
}

export { routes, scrollBehavior, beforeEach, afterEach }
