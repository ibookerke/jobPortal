<template>
    <v-form ref="cv_form"
            lazy-validation
            style="margin-bottom: 140px !important"
    >
        <v-container>
            <v-row>
                <v-col>
                    <h1>Your CV</h1>
                </v-col>
            </v-row>
            <personal-information ref="cv_personal_information"/>
            <experience/>
            <education/>
            <skills/>
            <v-row>
                <v-col>
                    <v-btn v-if="!cvEditType"
                           rounded
                           color="primary"
                           dark
                           @click="saveForm"
                    >
                        Create CV
                    </v-btn>

                    <v-btn v-else
                        rounded
                        color="primary"
                        dark
                        @click="updateForm"
                    >
                        Update CV
                    </v-btn>

                    <v-btn rounded
                           color="error"
                           dark
                           @click="$router.push({name: 'profile'})"
                    >
                        Cancel
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
            cvEditType: null,
            cvID: null,

            // information from users table
            user: {}
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

                let get = this.$store.getters;
                let exp = get.getCVExperienceRadio ? get.getCVExperience : [];
                let ed = get.getCVEducationRadio ? get.getCVEducation : [];
                axios.post(
                    '/api/save_seeker_cv',
                    {
                        'cv': get.getCVProfile,
                        'experience': exp,
                        'education': ed,
                        'skills': get.getCVSkills
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ${localStorage.token}`
                        }
                    }
                ).
                then(response => {
                    if (response.status === 201)
                    {
                        this.$store.commit('cvClearAll');
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
        updateForm() {
            this.$refs.cv_personal_information.$v.$touch();
            if (this.$refs.cv_personal_information.$v.$invalid)
            {
                this.submitStatus = 'ERROR';
            }
            else {
                // do your submit logic here
                this.submitStatus = 'PENDING';

                let get = this.$store.getters;
                let exp = get.getCVExperienceRadio ? get.getCVExperience : [];
                let ed = get.getCVEducationRadio ? get.getCVEducation : [];

                axios.post(
                    '/api/update_seeker_cv',
                    {
                        'cv': get.getCVProfile,
                        'experience': exp,
                        'education': ed,
                        'skills': get.getCVSkills,
                        'removedEd': get.getCVRemovedEducationArray,
                        'removedExp': get.getCVRemovedExperienceArray,
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ${localStorage.token}`
                        }
                    }
                ).then(response => {
                    if (response.status === 201) {
                        this.$store.commit('cvClearAll');
                        this.$router.push('/profile');
                    }
                }).catch(error => {
                    console.log(error.response.data);
                });

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
            }
        }
    },
    created() {
        this.user = this.user_info;

        this.cvEditType =  this.$store.getters.getCVEditType;

        if (this.cvEditType === null)
        {
            this.$router.push({name: 'login'});
        }
    },
    watch: {
        user_info() {
            this.user = this.user_info;
        }
    }
}
</script>

<style scoped>

</style>
