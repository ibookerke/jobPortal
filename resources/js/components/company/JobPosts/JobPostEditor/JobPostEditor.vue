<template>
    <v-form ref="job_form" style="margin-bottom: 140px">
        <v-container>
            <v-row>
                <v-col>
                    <h1 v-if="editorMode === 0">
                        Create Vacancy
                    </h1>
                    <h1 v-else>
                        Update Vacancy
                    </h1>
                </v-col>
            </v-row>
            <job-post :editorMode="editorMode"
                      :userID="user_id"
                      :companyID="company_id"
                      :currentJobPost="jobPost.job_post"
                      :currentWorkExperienceType="jobPost.work_experience_type"
                      @setJobPost="setJobPost"
                      ref="job_post"
            />
            <job-type-select :editorMode="editorMode"
                             :currentJobTypeArray="jobPost.job_type_array"
                             @setJobTypeArray="setJobTypeArray"
            />
            <job-skills :editorMode="editorMode"
                           :currentSkillArray="jobPost.skill_array"
                           @setSkillArray="setSkillArray"
            />
            <job-location :editorMode="editorMode"
                          :currentJobLocation="jobPost.job_location"
                          :currentJobLocationDetails="jobLocationDetails"
                          @setJobLocation="setJobLocation"
                          ref="job_location"/>
            <v-row>
                <v-col>
                    <v-btn v-if="editorMode === 0"
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

import JobTypeSelect from "./JobTypeSelect";
import JobLocation from "./JobLocation";
import JobSkills from "./JobSkills";
import JobPost from "./JobPost";

export default {
    mixins: [validationMixin],
    name: "JobPostEditor",
    components: {JobPost, JobSkills, JobLocation, JobTypeSelect},
    data() {
        return {
            editorMode: null,
            user_id: null,
            company_id: null,
            job_post_id: null,
            jobPost: {
                job_post: {},
                job_type_array: [],
                job_location: {},
                skill_array: [],
                job_location_details: {},
                work_experience_type: {},
            },
            jobLocationDetails: {},
        }
    },
    created() {
        let get = this.$store.getters;
        this.editorMode = get.getJobPostEditorMode;
        switch (this.editorMode) {
            case 0:
                break;
            case 1:
                this.jobPost = get.getJobPost;
                this.jobLocationDetails = this.jobPost.job_location_details;
                break;
            default:
                this.$router.push({name: 'login'});
        }
        this.user_id = get.getJobPostUserID;
        this.company_id = get.getJobPostCompanyID;
    },
    methods: {
        setJobPost(value) {
            this.jobPost.job_post = value;
        },
        setJobTypeArray(value) {
            this.jobPost.job_type_array = value;
        },
        setSkillArray(value) {
            this.jobPost.skill_array = value;
        },
        setJobLocation(value) {
            this.jobPost.job_location = value;
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

                delete this.jobPost.job_location_details;
                delete this.jobPost.work_experience_type;

                if (this.editorMode === 0) {this.create();}
                else if (this.editorMode === 1) {this.update();}

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
            }
        },
        create() {
            axios.post(
                '/api/create_job_post',
                {
                    'job_post': this.jobPost,
                    'user_id': this.user_id,
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
                    this.$router.push('/my_job_posts');
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        update() {
            axios.post(
                '/api/update_job_post',
                {
                    'job_post': this.jobPost,
                    'user_id': this.user_id,
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
                    this.$router.push('/my_job_posts');
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
