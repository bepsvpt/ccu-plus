let Vue = require('vue');

export default {
  '/': {
    name: 'home',
    component: Vue.extend({
      template: `<h1>Hello World!</h1>`
    })
  },
  '/auth/sign-in': {
    name: 'auth.sign-in',
    component: Vue.extend({
      template: `<h1>Hello World!</h1>`
    })
  },
  '/auth/sign-out': {
    name: 'auth.sign-out',
    component: Vue.extend({
      template: `<h1>Hello World!</h1>`
    })
  },
  '/auth/sign-up': {
    name: 'auth.sign-up',
    component: Vue.extend({
      template: `<h1>Hello World!</h1>`
    })
  }
}
