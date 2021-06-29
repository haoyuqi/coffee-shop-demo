import Vue from 'vue';
import VueRouter from 'vue-router';
import store from "./store.js";

Vue.use(VueRouter);

function requireAuth(to, from, next) {
    function proceed() {
        if (store.getters.getUserLoadStatus() === 2) {
            if (store.getters.getUser !== '') {
                next();
            } else {
                next('/home');
            }
        }
    }

    if (store.getters.getUserLoadStatus() !== 2) {
        store.dispatch('loadUser');

        store.watch(store.getters.getUserLoadStatus, function () {
            if (store.getters.getUserLoadStatus === 2) {
                proceed();
            }
        })
    } else {
        proceed();
    }
}

export default new VueRouter({
    routes: [
        {
            path: '/',
            redirect: {name:'home'},
            name: 'layout',
            component: Vue.component('Home', require('./pages/Layout.vue').default),
            children: [
                {
                    path: 'home',
                    name: 'home',
                    component: Vue.component('Home', require('./pages/Home.vue').default)
                },
                {
                    path: 'cafes',
                    name: 'cafes',
                    component: Vue.component('Cafes', require('./pages/Cafes.vue').default)
                },
                {
                    path: 'cafes/new',
                    name: 'newcafe',
                    component: Vue.component('home', require('./pages/NewCafe.vue').default),
                    beforeEnter: requireAuth
                },
                {
                    path: 'cafes/:id',
                    name: 'cafe',
                    component: Vue.component('Cafe', require('./pages/Cafe.vue').default)
                }
            ]
        },
    ]
});
