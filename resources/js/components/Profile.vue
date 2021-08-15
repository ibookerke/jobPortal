<template>
    <CompanyProfile v-if="user.user_type_id === 1"></CompanyProfile>
    <SeekerProfile v-else :user_info="user"></SeekerProfile>
</template>
<script>

import CompanyProfile from "./company/CompanyProfile";
import SeekerProfile from "./seeker/profile/Profile";

export default {
    name: "Profile",

    data() {
        return {
            user: {}
        }
    },

    components : {
        SeekerProfile,
        CompanyProfile
    },

    created() {
        if(!this.$store.getters.getUserLoadingStatus){
            this.user = this.$store.getters.getUserData
        }
    },
}
</script>

<style scoped>

</style>
