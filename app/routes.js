import VueRouter from 'vue-router'

import DriverView from './pages/DriverView'
import UserView from './pages/UserView'

let routes = [
    { name: 'driver-view', path: '/driver', component: DriverView },
    { name: 'user-view', path: '/', component: UserView},
];

let router = new VueRouter({
    mode: 'history',
    routes
});

export default router;
