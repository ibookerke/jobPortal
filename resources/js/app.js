/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
import colors from 'vuetify/lib/util/colors'

import Vuetify from "vuetify";
import router from "./app/router"
import store from "./app/store"

Vue.use(Vuetify)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('BaseApp', require('./components/BaseApp.vue').default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const opts = {
    icons: {
        iconfont: 'mdi'
    },
    theme: {
        themes: {
            light: {
                primary: '#6200EA',
                success: colors.green.darken1,
                secondary: colors.grey.darken1,
                error: colors.red.darken2,
                black: colors.black
            },
            dark: {
                primary: colors.lightBlue.lighten1,
                success: colors.green,
                secondary: colors.grey.darken2,
                error: colors.red,
                black: '#FFFFFF'
            },
        }
    },
}

export default new Vuetify(opts)

const vuetify = new Vuetify()

const app = new Vue({
    router,
    store,
    vuetify,
    el: '#app',
});
