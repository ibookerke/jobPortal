<template>
    <v-form ref="cv_form"
            lazy-validation
    >
        <v-container>
            <v-row>
                <v-col>
                    <h1>Your CV</h1>
                </v-col>
            </v-row>
            <personal-information v-model="profile"
                                  :user_id="user.id"
                                  ref="cv_personal_information"
            />
            <experience :user_id="user.id" v-model="experience"/>
            <education :user_id="user.id" v-model="education"/>
            <skills v-model="skills"/>
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

// validation
import { validationMixin } from 'vuelidate';

import PersonalInformation from "./PersonalInformation";
import Experience from "./Experience";
import Education from "./Education";
import Skills from "./Skills";


export default {
    mixins: [validationMixin],
    name: "SaveCV",
    components: {
        Skills,
        Education,
        Experience,
        PersonalInformation
    },
    data() {
        return {
            // information from users table
            user: {},

            // information from users cvs
            profile: {},

            // information from previous work experience
            experience: [],

            // information from previous education
            education: [],

            // information from skills
            skills: []
        }
    },
    props: [
        "user_info"
    ],
    methods: {
        saveForm() {
            this.$refs.cv_personal_information.$v.$touch();
            if (this.$refs.cv_personal_information.$v.$invalid)
            {
                this.submitStatus = 'ERROR';
            }
            else
            {
                // do your submit logic here
                this.submitStatus = 'PENDING';

                axios.post(
                    '/api/save_seeker_cv',
                    {
                        'cv': this.profile,
                        'experience': this.experience,
                        'education': this.education,
                        'skills': this.skills
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ${localStorage.token}`
                        }
                    }
                ).
                then(response => {
                    console.log(response.data)
                    if (response.status === 201)
                    {
                        this.$router.push('/profile');
                    }
                }).
                catch(error => {
                    console.log(error.response.data);
                });

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
            }
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
        }
    }
}
</script>

<style scoped>

</style>
