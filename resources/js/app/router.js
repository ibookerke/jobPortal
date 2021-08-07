import Vue from 'vue'
import VueRouter from "vue-router"
import store from "./store";

import HomeComponent from "../components/HomeComponent";
import AdminPage from "../components/admin/AdminPage";
import NotFound from "../components/NotFound";
import Location from "../components/admin/Location";
import AccessDenied from "../components/AccessDenied";
import EventEditor from "../components/admin/EventEditor";

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
        path: "/administrator",
        name: "admin_page",
        component: AdminPage,
    },
    {
        path: "/admin_locations",
        name: "admin_locations",
        component: Location,
        beforeEnter: (to, from, next) => {
            let user = store.getters.getUserData
            if(user.role === 2) {
                next()
            }
            else{
                next({name: 'home'})
            }
        }
    },
    {
        path: "/admin_events",
        name: "admin_events",
        component: EventEditor,
        beforeEnter: (to, from, next) => {
            let user = store.getters.getUserData
            if(user.role === 2) {
                next()
            }
            else{
                next({name: 'home'})
            }
        }
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
