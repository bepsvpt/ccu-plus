let Vue = require('vue');
let VueRouter = require('vue-router');

Vue.use(require('vue-resource'));
Vue.use(VueRouter);

Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift());

Vue.filter('urlify', function (value) {
  let urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;

  return value.replace(urlRegex, function (url) {
    return `<a href="${url}" target="_blank">${url}</a>`;
  })
});

import App from './templates/main.vue';
import Arrive from './components/arrive';
import Recaptcha from './components/recaptcha.vue';
import router from './routes';

Arrive();

let Router = new VueRouter();

Router.map(router.routes);
Router.start(App, 'main');
