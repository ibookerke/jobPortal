import Vue from 'vue'
import VueRouter from "vue-router"
import store from "./store";

import HomeComponent from "../components/HomeComponent";
import NotFound from "../components/NotFound";
import AccessDenied from "../components/AccessDenied";
import Profile from "../components/Profile";
import LoginComponent from "../components/auth/LoginComponent";
import Register from "../components/auth/Register";
import Applications from "../components/seeker/Applications";

Vue.use(VueRouter)


const originalPush = VueRouter.prototype.push

//in order to avoid the duplicated route error throwing
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

const routes = [
    {
        path: "/",
        name: "home",
        component: HomeComponent
    },
    {
        path: "/login",
        name: "login",
        component: LoginComponent,
    },
    {
        path: "/register",
        name: "register",
        component: Register
    },
    {
        path: "/profile",
        name : "profile",
        component: Profile
    },
    {
        path: "/applications",
        name: "application",
        component: Applications
    },
    {
        path: "/access_denied",
        name: "access_denied",
        component: AccessDenied
    },
    {
        path: "*",
        name: "NotFound",
        component : NotFound
    }

]

const router = new VueRouter({
    mode: "history",
    routes
})

export default router
