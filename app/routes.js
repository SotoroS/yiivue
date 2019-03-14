import VueRouter from 'vue-router'

import LandingPage from './pages/LandingPage'
import LoginPage from './pages/LoginPage'

let routes = [
    { name: 'index', path: '/', component: LandingPage },
    { name: 'login', path: '/login', component: LoginPage},
];

let router = new VueRouter({
    mode: 'history',
    routes
});

export default router;
