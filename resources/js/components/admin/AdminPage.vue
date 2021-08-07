<template>
    <div v-if="user.name">
        <pre>{{user}}</pre>

        <h1>Hello, {{user.name}}</h1>
    </div>
    <LoginComponent v-else @getUser="getUser"></LoginComponent>
</template>

<script>

import LoginComponent from "./LoginComponent";

export default {
    created() {
        this.user = this.user_info
    },

    name: "AdminPage",

    props: [
        "user_info"
    ],

    data() {
        return {
            user: {}
        }
    },

    components : {
        LoginComponent
    },

    watch: {
        user_info() {
            this.user = this.user_info
        }
    },

    methods: {
        getUser(user_data) {
            this.$emit("auth", user_data)
            this.user = user_data
        }
    }
}
</script>

<style scoped>

</style>
