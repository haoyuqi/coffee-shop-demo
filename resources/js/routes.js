import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './pages/Home';
import Cafes from "./pages/Cafes";
import NewCafe from "./pages/NewCafe";
import Cafe from "./pages/Cafe";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: '/',
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
                    component: Vue.component('Cafes', require('./pages/Cafe.vue').default)
                },
                {
                    path: 'cafes/new',
                    name: 'newcafe',
                    component: Vue.component('NewCafe', require('./pages/NewCafe.vue').default)
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
