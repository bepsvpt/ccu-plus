import signUp from './templates/sign-up.vue';
import coursesIndex from './templates/courses/index.vue';
import coursesShow from './templates/courses/show.vue';
import courseLite from './templates/ecourse-lite.vue';

let Vue = require('vue');

export default {
  routes: {
    '/': {
      name: 'home',
      component: Vue.extend({template: `<h1>Hello World!</h1>`})
    },
    '/sign-up': {
      name: 'sign-up',
      component: signUp
    },
    '/courses': {
      name: 'courses.index',
      component: coursesIndex
    },
    '/courses/:seriesId': {
      name: 'courses.show',
      component: coursesShow
    },
    '/ecourse-lite': {
      name: 'ecourse-lite',
      component: courseLite
    }
  }
}
