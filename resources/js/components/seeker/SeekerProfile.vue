<template>
    <div>
        <pre>{{user}}</pre>

        <h1>Hello, seeker {{user.email}}</h1>
    </div>
</template>

<script>
export default {
    name: "SeekerProfile",
    data() {
        return {
            user: {}
        }
    },
    props: [
        "user_info"
    ],
    watch: {
        user_info() {
            this.user = this.user_info
        }
    },

}
</script>

<style scoped>

</style>
