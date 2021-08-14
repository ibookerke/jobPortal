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
import CompanyJobPosts from "../components/company/JobPosts/CompanyJobPosts";
import CVEditor from "../components/seeker/CVEditor/Form";
import CompanyEditor from "../components/company/CompanyEditor";
import JobPostEditor from "../components/company/JobPosts/JobPostEditor/JobPostEditor";
import Companies from "../components/company/Companies";
import JobPosts from "../components/company/JobPosts/JobPosts";
import CVs from "../components/seeker/CVs";
import CV from "../components/seeker/CV";

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
    cv_editor: 2,
    edit_company: 1,
    edit_job_post: 1,
    cvs: 1,


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
        component: CompanyJobPosts
    },
    {
        path: "/cv_editor",
        name: "cv_editor",
        component: CVEditor
    },
    {
        path: "/edit_company",
        name: "edit_company",
        component: CompanyEditor
    },
    {
        path: "/edit_job_post",
        name: "edit_job_post",
        component: JobPostEditor
    },
    {
        path: "/companies",
        name: "companies",
        component: Companies
    },
    // {
    //     path: "/companies/:id",
    //     name: "company_id",
    //     component: Companies
    // },
    {
        path: "/job_posts",
        name: "job_posts",
        component: JobPosts
    },
    // {
    //     path: "/job_posts/:id",
    //     name: "job_post_id",
    //     component: JobPosts
    // },
    {
        path: "/cvs",
        name: "cvs",
        component: CVs
    },
    {
        path: "/cvs/:id",
        name: "cv",
        component: CV
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
