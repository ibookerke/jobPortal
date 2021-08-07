<template>
    <v-container>
        <v-row>
            <v-col>
                <v-btn
                    @click="$router.push({name: 'save_cv'})"
                    rounded
                    color="primary"
                    dark
                >
                    Create CV
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
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
