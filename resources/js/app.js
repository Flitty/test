
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import router                from './router';
import store                 from './store';
import App                   from './components/App.vue';
import {get, del, post, put} from './helpers/api'
import {handleErrors}        from './helpers/errors';
import Notify                from "vue2-notify"

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

window.Vue.prototype.$get = get;
window.Vue.prototype.$del = del;
window.Vue.prototype.$post = post;
window.Vue.prototype.$put = put;
window.Vue.prototype.$handleErrors = handleErrors;

window.Vue.use(Notify);

const app = new Vue({
    el: '#app',
    store,
    template: '<app></app>',
    components: {App},
    router
});
