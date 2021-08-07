<template>
    <div>
        <Navbar :user="user"></Navbar>
        <router-view :user_info="user" @auth="login"></router-view>
    </div>
</template>

<script>

import Navbar from "./Navbar";

export default {
    name: "BaseApp",
    data(){
        return  {
            user: {}
        }
    },
    components: {
        Navbar
    },

    beforeCreate() {
        let token = localStorage.getItem("token")

        if(token) {
            axios.get("/api/auth/user-profile", {
                headers: {
                    "Authorization" : "Bearer " + token
                }
            }).then(response => {
                this.user = response.data
                this.$store.commit("setUserInfo", response.data)

            }).catch(error=> {
                console.log("error", error)
            })
        }
    },

    methods: {
        login(user_data) {
            this.user = user_data
        }
    },

}
</script>

<style scoped>

</style>
