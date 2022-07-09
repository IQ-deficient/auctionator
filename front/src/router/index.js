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

    {path: '/', redirect: '/home'},
    {path: '/home', name: 'Home', component: Home, meta: {title: 'Home'}},
    {path: '/about', name: 'About', component: () => import('../views/About.vue'), meta: {title: 'About'}},
    {
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        path: '/contact',
        name: 'Contact',
        component: () => import(/* webpackChunkName: "about" */ '../views/Contact.vue'),
        meta: {title: 'User Profile'}
    },
    {path: '/login', name: 'Login', component: Login, meta: {title: 'Login'}},
    {path: '/register', name: 'Register', component: Register, meta: {title: 'Register'}},
    {path: '/admin-auction', name: 'AdminAuction', component: AdminAuction, meta: {title: 'Auction Management'}},
    {path: '/admin-user', name: 'AdminUser', component: AdminUser, meta: {title: 'User Management'}},
    {path: '/user-profile', name: 'UserProfile', component: UserProfile, meta: {title: 'User Profile'}},
    {path: '/auction-browse', name: 'AuctionBrowse', component: AuctionBrowse, meta: {title: 'Browse'}},
    {path: '/bids', name: 'Bids', component: Bids, meta: {title: 'Your Bids'}},
    {path: '/history', name: 'History', component: History, meta: {title: 'Owned Auctions'}},
    // Non-existent route catch
    {path: '/:catchAll(.*)', name: 'NotFound', component: PageNotFound, meta: {title: 'Not Found'}},
]

const router = new VueRouter({
    // This now somehow works without any server configuration defined in documentation
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title + ' - ' + process.env.VUE_APP_TITLE || process.env.VUE_APP_TITLE
    next()
})

export default router
