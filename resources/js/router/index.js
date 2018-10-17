import Vue                  from 'vue';
import VueRouter            from 'vue-router';

import store                from '../store';
import Login                from '../components/pages/Login.vue';
import Register             from '../components/pages/Register.vue';
import NotFound             from '../components/pages/NotFound.vue';
import Home                 from '../components/Home.vue';
import Profile              from '../components/pages/Profile.vue';
import Calculations         from '../components/pages/Calculations.vue';
import Figures         from '../components/pages/Figures.vue';

Vue.use(VueRouter);

/**
 * @type {VueRouter}
 */
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            components: {user: Home},
            name: 'home',
            meta: {
                requiresAuth: true
            },
            children: [
                {path: 'profile',      component: Profile,      name: 'profile'},
                {path: 'calculations', component: Calculations, name: 'calculations'},
                {path: 'figures',      component: Figures,      name: 'figures'}
            ]
        },
        {path: '/login',     components: {guest: Login},    name: 'login', meta: {requiresGuest: true}},
        {path: '/register',  components: {guest: Register}, name: 'register', meta: {requiresGuest: true}},
        {path: '/not-found', components: {any: NotFound},   name: 'not-found'},
        {path: '/*',         redirect:   {name: 'not-found'}}
    ]
});


router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        store.dispatch('setToken');

        if (store.state.api_token) {
            store.dispatch('setLastRoute', to.fullPath);

            return next();
        } else {
            return next({path: '/login'});
        }
    }
    if (to.matched.some(record => record.meta.requiresGuest)) {
        store.dispatch('setToken');
        if (store.state.api_token) {
            return next({name: 'home'});
        } else {
            return next();
        }
    }

    return next();
});

export default router;
