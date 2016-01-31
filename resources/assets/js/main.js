let Vue = require('vue');
let VueRouter = require('vue-router');

Vue.use(require('vue-resource'));
Vue.use(VueRouter);

import App from './components/main.vue';
import routes from './routes';

let router = new VueRouter();

router.map(routes);

router.start(App, 'main');
