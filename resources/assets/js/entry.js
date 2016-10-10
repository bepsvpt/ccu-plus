import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'

import httpInterceptors from './init/httpInterceptors'
import * as Router from './router'

Vue.use(Vuex)
Vue.use(VueResource)

Vue.http.interceptors.push(httpInterceptors)

// VueRouter must use in the end.
Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  linkActiveClass: 'is-active',
  scrollBehavior: Router.scrollBehavior,
  routes: Router.routes
})

router.beforeEach(Router.beforeEach)
router.afterEach(Router.afterEach)

new Vue({ router }).$mount('#ccu-plus')
