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
        this.getUserLoad()

        this.unwatch = this.$store.watch(
            (state, getters) => getters.getUserLoadingStatus,
            (newValue, oldValue) => {
                this.loading = newValue
                if(!newValue) {
                    this.user = this.$store.getters.getUserData
                }
            }
        )
    },

    methods: {
        login(user_data) {
            this.user = user_data
        },

        getUserLoad() {
            let token = localStorage.getItem("token")
            if(token){
                this.$store.dispatch("LoadUser", token)
            }
            else{
                this.loading = false
                this.$store.commit("stopUserLoading")
            }
        },
    },

    beforeDestroy() {

        this.unwatch();
    },

}
</script>

<style scoped>
.loader {
    background: #8E8E8E;
    width: 100%;
    height: 100vh;
}
</style>
