import Vue from 'vue';
import Vuex from "vuex";

import user_info from "./modules/user_info";
import schema from "./modules/schema";
import cvstorage from "./modules/cvstorage"
import company from "./modules/company"

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        user_info,
        schema,
        cvstorage,
        company
    }
})


export default store;
