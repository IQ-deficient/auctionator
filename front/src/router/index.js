import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from "../views/Login.vue";
import Register from "../views/Register";
import AdminAuction from "../views/AdminAuction";
import AdminUser from "../views/AdminUser";
import UserProfile from "../views/UserProfile";
import AuctionBrowse from "../views/AuctionBrowse";
import PageNotFound from "../views/PageNotFound";
import Bids from '../views/Bids';
import History from '../views/History';


Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        redirect: '/home'
    },
    {
        path: '/home',
        name: 'Home',
        component: Home
    },
    {
        path: '/about',
        name: 'About',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
    },

    {path: '/login', name: 'Login', component: Login},
    {path: '/register', name: 'Register', component: Register},
    {path: '/admin-auction', name: 'AdminAuction', component: AdminAuction},
    {path: '/admin-user', name: 'AdminUser', component: AdminUser},
    {path: '/user-profile', name: 'UserProfile', component: UserProfile},
    {path: '/auction-browse', name: 'AuctionBrowse', component: AuctionBrowse},
    {path: '/bids', name: 'Bids', component: Bids},
    {path: '/history', name: 'History', component: History},
    {path: '/:catchAll(.*)', name: 'NotFound', component: PageNotFound},
]

const router = new VueRouter({
    // This now somehow works without any server configuration defined in documentation
    mode: 'history',
    routes
})

export default router
