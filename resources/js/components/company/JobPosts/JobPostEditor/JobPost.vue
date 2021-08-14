<template>
    <div>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="jobPost.job_title"
                    label="Job Title"
                    outlined

                    :error-messages="jobTitleErrors"
                    :counter="255"
                    @input="$v.jobPost.job_title.$touch()"
                    @blur="$v.jobPost.job_title.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-textarea
                    v-model.trim="jobPost.job_description"
                    label="Job Description"
                    outlined

                    :error-messages="jobDescriptionErrors"
                    :counter="3000"
                    @input="$v.jobPost.job_description.$touch()"
                    @blur="$v.jobPost.job_description.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model="jobPost.salary"
                    label="Salary"
                    outlined
                    type="number"
                    prefix="KZT"

                    :error-messages="salaryErrors"
                    @input="$v.jobPost.salary.$touch()"
                    @blur="$v.jobPost.salary.$touch()"
                />
            </v-col>
        </v-row>
        <v-row v-if="editorMode !== 0">
            <v-col>
                <v-checkbox
                    v-model="jobPost.is_active"
                    label="Is it still active?"
                    outlined
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-select
                    v-model="selectedWorkExperience"
                    :items="workExperienceArray"
                    item-text="name"
                    item-value="id"
                    label="Work Experience"
                    outlined
                    dense
                    return-object

                    :error-messages="workExperienceTypeErrors"
                    @select="$v.jobPost.work_experience_type.$touch()"
                    @blur="$v.jobPost.work_experience_type.$touch()"
                />
            </v-col>
        </v-row>
    </div>
</template>

<script>
// validation
import { validationMixin } from 'vuelidate';
import { required, minLength, maxLength, minValue, integer, maxValue } from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],
    name: "JobPost",
    props: ['editorMode', 'userID', 'companyID', 'currentJobPost', 'currentWorkExperienceType'],
    data() {
        return {
            jobPost: {
                user_id: null,
                company_id: null,
                location_id: null,
                is_active: 1,
                work_experience_type: null,
                job_description: null,
                job_title: null,
                salary: null
            },
            workExperienceArray: [],
            selectedWorkExperience: null
        }
    },
    created() {
        this.getWorkExperienceArray();
        switch (this.editorMode) {
            case 0:
                this.jobPost.user_id = this.userID;
                this.jobPost.company_id = this.companyID;
                break;
            case 1:
                this.jobPost = this.currentJobPost;
                this.selectedWorkExperience = this.currentWorkExperienceType;
                break;
            default:
                this.$router.push({name: 'login'});
        }
    },
    methods: {
        getWorkExperienceArray() {
            axios.post(
                '/api/get_work_experience_array',
                {
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.workExperienceArray = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        }
    },
    watch: {
        jobPost: {
            handler: function (value) {
                this.$emit('setJobPost', value);
            },
            deep: true
        },
        selectedWorkExperience: function (value) {
            if (this.editorMode !== 1) {this.jobPost.work_experience_type = value.id;}
        }
    },
    validations: {
        jobPost: {
            job_title: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            job_description: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(3000)
            },
            salary: {
                integer,
                minValue: minValue(0),
                maxValue: maxValue(2147483647)
            },
            work_experience_type: {
                required
            }
        }
    },
    computed: {
        jobTitleErrors () {
            const errors = [];
            if (!this.$v.jobPost.job_title.$dirty) return errors;
            !this.$v.jobPost.job_title.minLength && errors.push('Job title must be at least 2 characters long');
            !this.$v.jobPost.job_title.maxLength && errors.push('Job title must be at most 255 characters long');
            !this.$v.jobPost.job_title.required && errors.push('Job title is required.');
            return errors;
        },
        jobDescriptionErrors () {
            const errors = [];
            if (!this.$v.jobPost.job_description.$dirty) return errors;
            !this.$v.jobPost.job_description.minLength && errors.push('Job description must be at least 2 characters long');
            !this.$v.jobPost.job_description.maxLength && errors.push('Job description must be at most 3000 characters long');
            !this.$v.jobPost.job_description.required && errors.push('Job description is required.');
            return errors;
        },
        salaryErrors () {
            const errors = [];
            if (!this.$v.jobPost.salary.$dirty) return errors;
            !this.$v.jobPost.salary.maxValue && errors.push('Salary must be lower than 2147483647');
            !this.$v.jobPost.salary.minValue && errors.push('Salary must be positive');
            !this.$v.jobPost.salary.integer && errors.push('Salary must be an integer');
            return errors;
        },
        workExperienceTypeErrors () {
            const errors = [];
            if (!this.$v.jobPost.work_experience_type.$dirty) return errors;
            !this.$v.jobPost.work_experience_type.required && errors.push('Work experience is required.');
            return errors;
        },
    },
}
</script>

<style scoped>

</style>
