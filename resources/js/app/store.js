import Vue from 'vue';
import Vuex from "vuex";

import user_info from "./modules/user_info";
import schema from "./modules/schema";
import seeker from "./modules/seeker";
import jobPosts from "./modules/jobPosts";
import company from "./modules/company";
import grid from "./modules/grid";

Vue.use(Vuex)

const store = new Vuex.Store({
    state:{
        search: ""
    },
    mutations:{
        setSearch(state, value){
            state.search = value
        }
    },

    getters:{
        getSearch(state) {
            return state.search
        }
    },

    modules: {
        user_info,
        schema,
        seeker,
        jobPosts,
        company,
        grid
    }
})


export default store;
