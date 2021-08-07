<template>
    <CompanyProfile v-if="user.user_type_id === 1" :user_info="user"></CompanyProfile>
    <SeekerProfile v-else :user_info="user"></SeekerProfile>
</template>
<script>

import CompanyProfile from "./company/CompanyProfile";
import SeekerProfile from "./seeker/SeekerProfile";

export default {
    name: "Profile",

    props: [
        "user_info"
    ],

    data() {
        return {
            user: {}
        }
    },

    components : {
        SeekerProfile,
        CompanyProfile

    },

    watch: {
        user_info() {
            this.user = this.user_info
        }
    },

    created() {
        this.user = this.user_info
    },

    methods: {

    }
}
</script>

<style scoped>

</style>
