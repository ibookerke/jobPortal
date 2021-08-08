<template>
    <v-form>
        <v-container>
            <v-row>
                <v-col>
                    <h1>Your CV</h1>
                </v-col>
            </v-row>
            <personal-information v-model="profile"
                                  :user_id="user.id"
            />
            <experience :user_id="user.id"/>
            <v-row>
                <v-col>
                    <v-btn
                        rounded
                        color="primary"
                        dark
                        @click="saveForm"
                    >
                        Create CV
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import PersonalInformation from "./PersonalInformation";
import Experience from "./Experience";


export default {
    name: "SaveCV",
    components: {
        Experience,
        PersonalInformation
    },
    data() {
        return {
            // information from users table
            user: {},

            // information from users cvs
            profile: {},
        }
    },
    props: [
        "user_info"
    ],
    methods: {
        saveForm() {
            console.log(this.profile)
        },
    },
    created() {
        this.user = this.user_info;
    },
    watch: {
        // profile: {
        //     handler: function (val) {
        //         this.$store.commit("cvUpdateProfile", val)
        //     },
        //     deep : true
        // },
        user_info() {
            this.user = this.user_info;
        },
        // profile: function(val) {
        //     console.log(val);
        // }
    }
}
</script>

<style scoped>

</style>
