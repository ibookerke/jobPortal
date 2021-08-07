import Vue from 'vue';
import Vuex from "vuex";

import user_info from "./modules/user_info";
import schema from "./modules/schema";

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        user_info,
        schema
    }
})


export default store;
