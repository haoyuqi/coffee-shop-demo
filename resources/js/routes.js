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
            name: 'home',
            component: Home
        },
        {
            path: 'cafes',
            name: 'cafes',
            component: Cafes
        },
        {
            path: 'cafes/new',
            name: 'newcafe',
            component: NewCafe
        },
        {
            path: 'cafes/:id',
            name: 'cafe',
            component: Cafe
        }
    ]
});
