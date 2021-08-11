<template>
    <v-form ref="job_form" style="margin-bottom: 140px">
        <v-container>
            <v-row>
                <v-col>
                    <h1 v-if="editType">
                        Create Vacancy
                    </h1>
                    <h1 v-else>
                        Update Vacancy
                    </h1>
                </v-col>
            </v-row>
            <job-post :editType="editType" ref="job_post"/>
            <job-type-select :editType="editType"/>
            <job-skill-set :editType="editType"/>
            <job-location :editType="editType" ref="job_location"/>
            <v-row>
                <v-col>
                    <v-btn v-if="editType"
                           rounded
                           color="primary"
                           dark
                           @click="validate"
                    >
                        Create CV
                    </v-btn>
                    <v-btn v-else
                           rounded
                           color="primary"
                           dark
                           @click="validate"
                    >
                        Update CV
                    </v-btn>

                    <v-btn rounded
                           color="error"
                           dark
                           @click="cancel"
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

import JobTypeSelect from "./job/JobTypeSelect";
import JobLocation from "./job/JobLocation";
import JobSkillSet from "./job/JobSkillSet";
import JobPost from "./job/JobPost";

export default {
    mixins: [validationMixin],
    name: "JobPostEditor",
    components: {JobPost, JobSkillSet, JobLocation, JobTypeSelect},
    data() {
        return {
            editType: null
        }
    },
    created() {
        let store = this.$store;
        let get = this.$store.getters;

        this.editType = get.getJPEditType; // create or update mode

        if (this.editType === null)
        {
            this.$router.push({name: 'my_job_posts'}); // neither update (false), nor delete (true), so redirect
        }
        else
        {
            store.commit('setJPUserID', get.getUserData.id);
            store.commit('setJPCompanyID', get.getCompanyData.id);
        }
    },
    methods: {
        getData(post_id) {
            console.log('getData');
        },
        validate() {
            this.$refs.job_post.$v.$touch();
            this.$refs.job_location.$v.$touch();
            if (this.$refs.job_post.$v.$invalid || this.$refs.job_location.$v.$invalid)
            {
                this.submitStatus = 'ERROR';
            }
            else {
                // do your submit logic here
                this.submitStatus = 'PENDING';

                let get = this.$store.getters;
                let jobPost = get.getJPJobPost;
                let jobLocation = get.getJPJobLocation;
                let skills = get.getJPSkills;
                let jobTypes = get.getJPJobTypes;

                if (this.editType)
                {
                    this.create(jobPost, jobLocation, skills, jobTypes);
                }
                else
                {
                    this.update(jobPost, jobLocation, skills, jobTypes);
                }

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
            }
        },
        create(jobPost, jobLocation, skills, jobTypes)
        {
            axios.post(
                '/api/create_job_post',
                {
                    'jobPost': jobPost,
                    'jobLocation': jobLocation,
                    'skills': skills,
                    'jobTypes': jobTypes,
                    'userID': this.$store.getters.getJPUserID
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
                    this.$store.commit('jpClearAll');
                    this.$router.push('/profile');
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        update(jobPost, jobLocation, skills, jobTypes) {
            axios.post(
                '/api/update_job_post',
                {
                    'jobPost': jobPost,
                    'jobLocation': jobLocation,
                    'skills': skills,
                    'jobTypes': jobTypes,
                    'userID': this.$store.getters.getJPUserID
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
                    this.$store.commit('jpClearAll');
                    this.$router.push('/profile');
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        cancel() {
            this.$router.push({name: 'my_job_posts'});
        }
    }
}
</script>

<style scoped>

</style>
