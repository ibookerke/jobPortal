<template>
    <v-container>
        <v-row>
            <v-col>
                <router-link
                    :to="{ name: 'save-cv' }"
                    :user_info="user"
                >
                    <v-btn
                        rounded
                        color="primary"
                        dark
                    >
                        Create CV
                    </v-btn>
                </router-link>
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
