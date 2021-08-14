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
            <personal-information :editorMode="editorMode"
                                  :currentPersonalInformation="cv.personalInformation"
                                  @setPersonalInformation="setPersonalInformation"
                                  ref="cv_personal_information"/>
            <experience :editorMode="editorMode"
                        :currentExperienceArray="cv.experienceArray"
                        @setExperience="setExperience"/>
            <education :editorMode="editorMode"
                       :currentEducationArray="cv.educationArray"
                       @setEducation="setEducation"/>
            <skills :editorMode="editorMode"
                    :currentSkillArray="cv.skillArray"
                    @setSkills="setSkills"/>
            <v-row>
                <v-col>
                    <v-btn v-if="!editorMode"
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
import Experience from "./Experience/Experience";
import Education from "./Education/Education";
import Skills from "./Skills";

export default {
    mixins: [validationMixin],
    name: "CVEditor",
    components: {Skills, Education, Experience, PersonalInformation},
    props: ["user_info"],
    data() {
        return {
            editorMode: null,
            user_id: null,
            cv: {
                personalInformation: {},
                experienceArray: [],
                educationArray: [],
                skillArray: [],
            }
        }
    },
    created() {
        let get = this.$store.getters;
        this.user_id = get.getSeekerUserID;
        this.editorMode =  this.$store.getters.getCVEditorMode;

        switch (this.editorMode) {
            case 0:
                break;
            case 1:
                this.cv = get.getSeekerCV;
                break;
            default:
                this.$router.push({name: 'login'});
        }
    },
    watch: {
        user_info() {
            this.user = this.user_info;
        }
    },
    methods: {
        setPersonalInformation(value) {
            this.cv.personalInformation = value;
        },
        setExperience(value) {
            this.cv.experienceArray = value;
        },
        setEducation(value) {
            this.cv.educationArray = value;
        },
        setSkills(value) {
            this.cv.skillArray = value;
        },
        saveForm() {
            this.$refs.cv_personal_information.$v.$touch();
            if (this.$refs.cv_personal_information.$v.$invalid)
            {
                this.submitStatus = 'ERROR';
            }
            else
            {
                this.submitStatus = 'PENDING';

                axios.post(
                    '/api/save_seeker_cv',
                    {
                        'cv': this.cv,
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

                axios.post(
                    '/api/update_seeker_cv',
                    {
                        'cv': this.cv
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ${localStorage.token}`
                        }
                    }
                ).then(response => {
                    console.log(response.data)
                    if (response.status === 201) {
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
}
</script>

<style scoped>

</style>
