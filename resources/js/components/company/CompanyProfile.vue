<template>
    <div>
        <pre>{{user}}</pre>

        <h1>Hello, company  {{user.email}}</h1>
    </div>
</template>

<script>
export default {
    name: "CompanyProfile",
    data() {
        return {
            user: {}
        }
    },
    props: [
        "user_info"
    ],

    created() {
        this.user = this.user_info
    },
}
</script>

<style scoped>

</style>
