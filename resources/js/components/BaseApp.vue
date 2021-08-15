<template>
    <div>
        <Navbar :user_info="user"></Navbar>
        <router-view v-if="!loading" :user_info="user" @getUser="login"></router-view>
        <div class="loader" v-else>
            <v-progress-linear
                color="white"
                indeterminate
            ></v-progress-linear>
        </div>

        <v-footer
            dark
            padless
            class="footer"
        >
            <v-card
                flat
                tile
                class="white--text text-center"
                :color="user.email ? 'deep-purple accent-4' : 'green accent-4'"
                width="100%"
            >

                <v-card-text>
                    <v-btn
                        class="mx-4 white--text"
                        icon
                    >
                        <v-icon size="24px">
                            mdi-facebook
                        </v-icon>
                    </v-btn>
                    <v-btn
                        class="mx-4 white--text"
                        icon
                    >
                        <v-icon size="24px">
                            mdi-twitter
                        </v-icon>
                    </v-btn>
                    <v-btn
                        class="mx-4 white--text"
                        icon
                    >
                        <v-icon size="24px">
                            mdi-linkedin
                        </v-icon>
                    </v-btn>
                    <v-btn
                        class="mx-4 white--text"
                        icon
                    >
                        <v-icon size="24px">
                            mdi-instagram
                        </v-icon>
                    </v-btn>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-text class="white--text">
                    {{ new Date().getFullYear() }} — <strong>JobPortal</strong>
                </v-card-text>
            </v-card>
        </v-footer>
    </div>

</template>

<script>

import Navbar from "./Navbar";

export default {
    name: "BaseApp",
    data(){
        return  {
            user: {},
            loading: true
        }
    },
    components: {
        Navbar
    },

    created() {
        //check for auth token existence
        let token = localStorage.getItem("token")
        if(token){
            //if localstorage have token loading the user data connected with token
            this.$store.dispatch("LoadUser", token)
        }
        else{
            //not authenticated
            this.loading = false
            this.$store.commit("stopUserLoading")
        }

        this.unwatch = this.$store.watch(
            (state, getters) => getters.getUserLoadingStatus,
            (newValue, oldValue) => {
                if(!newValue) {
                    this.user = this.$store.getters.getUserData

                    if (this.user.user_type_id === 1) {
                        this.watchCompanyDataLoading()
                    }
                    else{
                        this.loading = false
                    }
                }
            }
        )
    },

    methods: {
        watchCompanyDataLoading(){
            //fetching data from db
            this.$store.dispatch("loadCompany", {
                id: this.user.id,
                token : localStorage.getItem("token")
            })
            //watching for company data loading
            this.unwatch = this.$store.watch(
                (state, getters) => getters.getCompanyLoading,
                (newValue, oldValue) => {
                    if(!newValue){
                        this.loading = false
                    }
                }
            )

        },

        login(user_data) {
            this.user = user_data
        },
    },

    watch: {

    },

    beforeDestroy() {
        this.unwatch();
    },

}
</script>

<style>
.footer {
    width: 100%;
    position: absolute;
    left: 0;
    bottom: 0;
}
.loader {
    background: #8E8E8E;
    width: 100%;
    height: 95vh;
}

</style>
