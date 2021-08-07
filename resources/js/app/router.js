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
import MyJobPosts from "../components/company/MyJobPosts";
import SaveCV from "../components/SaveCV";

Vue.use(VueRouter)


const originalPush = VueRouter.prototype.push

//in order to avoid the duplicated route error throwing
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}
var access = {
    application : 2,
    my_job_posts : 1,

}

function is_allowed(path) {
    let user = store.getters.getUserData

    return access[path.name] === user.user_type_id;
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
        component: Applications,
        beforeEnter(to, from, next) {
            if(is_allowed(to)){
                next()
            }
            else{
                next({name: "access_denied"})
            }
        }
    },
    {
        path: "/my_job_posts",
        name: "my_job_posts",
        component: MyJobPosts,
        beforeEnter(to, from, next) {
            if(is_allowed(to)){
                next()
            }
            else{
                next({name: "access_denied"})
            }
        }
    },
    {
        path: "/access_denied",
        name: "access_denied",
        component: AccessDenied
    },
    {
        path: "/СV-editor",
        name: "save-cv",
        component: SaveCV
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
