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
import SaveCV from "../components/seeker/CVEditor/SaveCV";
import CompanyEditor from "../components/company/CompanyEditor";

Vue.use(VueRouter)


const originalPush = VueRouter.prototype.push

//in order to avoid the duplicated route error throwing
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

//routing access setting
// 0 for authenticated only; 1 for companies; 2 for job seekers; 3 for superuser only; 4 for not authenticated users only
var access = {
    application : 2,
    my_job_posts : 1,
    save_cv: 2,
    edit_company: 1,


    profile: 0,
    login: 4,
    register: 4
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
        path: "/my_job_posts",
        name: "my_job_posts",
        component: MyJobPosts
    },
    {
        path: "/cv_editor",
        name: "save_cv",
        component: SaveCV
    },
    {
        path: "edit_company",
        name: "edit_company",
        component: CompanyEditor
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

//before entering the url checking if the user data have loaded and the user have access to the url
router.beforeEach((to, from, next) => {

    //looking if the url needs access check
    if(Object.keys(access).includes(to.name)){
        //url proceeding
        function proceed() {
            if(store.getters.getAuthStatus) {
                if(access[to.name] === 0) {
                    next()
                }
                else if(access[to.name] === 4) {
                    next({name: "profile"})
                }
                else{
                    let user = store.getters.getUserData
                    if(user.user_type_id === access[to.name]){
                        next()
                    }
                    else{
                        next({name: "access_denied"})
                    }
                }
            }
            else{
                if(access[to.name] === 0) {
                    next({name: "login"})
                }
                else if(access[to.name] === 4) {
                    next()
                }
                else{
                    next({name: "access_denied"})
                }

            }
        }

        //making watch on the store user loading status waiting till the loading have ended
        if(store.getters.getUserLoadingStatus){
            store.watch(
                (state, getters) => getters.getUserLoadingStatus,
                (newValue, oldValue) => {
                    console.log(`Updating from ${oldValue} to ${newValue}`);

                    if(!newValue) {
                        console.log("new value", store.getters.getUserData)
                        proceed()
                    }
                }
            )
        }
        else{
            proceed()
        }
    }
    else{
        next()
    }

})

export default router
