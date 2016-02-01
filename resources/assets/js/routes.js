import signUp from './components/sign-up.vue';

let Vue = require('vue');

export default {
  routes: {
    '/': {
      name: 'home',
      component: Vue.extend({
        template: `<h1>Hello World!</h1>`
      })
    },
    '/sign-up': {
      name: 'sign-up',
      component: signUp
    },
    '/courses': {
      name: 'courses',
      component: Vue.extend({
        template: `<h1>課程評論</h1>`
      })
    },
    '/ecourse-lite': {
      name: 'ecourse-lite',
      component: Vue.extend({
        template: `<h1>Ecourse Lite</h1>`
      })
    }
  }
}
