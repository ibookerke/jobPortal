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
import SaveCV from "../components/seeker/SaveCV";
import Loading from "../components/Loading";

Vue.use(VueRouter)


const originalPush = VueRouter.prototype.push

//in order to avoid the duplicated route error throwing
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}
var access = {
    application : 2,
    my_job_posts : 1,
    profile: 0,
    save_cv: 2
}

const userLoadCheck = (to, from, next) => {
    // we must wait for the store to be initialized
    if (store.getters.getUserLoadingStatus) {
        console.log('loading')

        // store.watch(
        //     (state) => store.getters.user_loading,
        //     (value) => {
        //         console.log("rara", value)
        //         if (value === false){
        //             if(access[to.name] === 0) {
        //                 next()
        //             }
        //             else{
        //                 let user = store.getters.getUserData
        //                 if(user.user_type_id === access[to.name]){
        //                     next()
        //                 }
        //                 else{
        //                     next({name: "access_denied"})
        //                 }
        //             }
        //         }
        //     }
        // )
    }
    else{
        next("/")
    }
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
        component: Profile,
        beforeEnter: userLoadCheck
    },
    {
        path: "/applications",
        name: "application",
        component: Applications,
        beforeEnter: userLoadCheck
    },
    {
        path: "/my_job_posts",
        name: "my_job_posts",
        component: MyJobPosts,
        beforeEnter: userLoadCheck
    },
    {
        path: "/cv_editor",
        name: "save_cv",
        component: SaveCV,
        beforeEnter: userLoadCheck
    },
    {
        path: "/access_denied",
        name: "access_denied",
        component: AccessDenied
    },
    {
        path: "/loading",
        name: "loading_screen",
        component: Loading
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
