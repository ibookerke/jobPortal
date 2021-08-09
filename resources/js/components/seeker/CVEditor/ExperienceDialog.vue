<template>
    <v-dialog
        v-model="dialog"
        persistent
        max-width="600px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary"
                   dark
                   v-bind="attrs"
                   v-on="on"
            >
                Add work
            </v-btn>
        </template>
        <v-card>
            <v-form>
                <v-container>
                    <v-row>
                        <v-col>
                            <v-menu
                                v-model="startDateMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="experience.start_date"
                                        :label="labels.start_date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"

                                        :error-messages="startDateErrors"
                                        required
                                        @input="$v.experience.start_date.$touch()"
                                        @blur="$v.experience.start_date.$touch()"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="experience.start_date"
                                    @input="startDateMenu = false"
                                    :max="currentDate"
                                    elevation="15"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="3">
                            <v-checkbox
                                v-model="experience.is_current_job"
                                :label="labels.is_current_job"
                            ></v-checkbox>
                        </v-col>
                        <v-col cols="9" v-if="!experience.is_current_job">
                            <v-menu
                                v-model="endDateMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model.trim="experience.end_date"
                                        :label="labels.end_date"
                                        required
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model.trim="experience.end_date"
                                    @input="endDateMenu = false"
                                    :min="experience.start_date"
                                    :max="currentDate"
                                    elevation="15"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model.trim="experience.company_name"
                                :label="labels.company_name"
                                outlined

                                :error-messages="companyNameErrors"
                                :counter="255"
                                required
                                @input="$v.experience.company_name.$touch()"
                                @blur="$v.experience.company_name.$touch()"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model.trim="experience.job_title"
                                :label="labels.job_title"
                                outlined

                                :error-messages="jobTitleErrors"
                                :counter="255"
                                required
                                @input="$v.experience.job_title.$touch()"
                                @blur="$v.experience.job_title.$touch()"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-textarea
                                v-model.trim="experience.job_description"
                                :label="labels.job_description"
                                outlined

                                :error-messages="jobDescriptionErrors"
                                :counter="4000"
                                required
                                @input="$v.experience.job_description.$touch()"
                                @blur="$v.experience.job_description.$touch()"
                            ></v-textarea>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-spacer></v-spacer>
                        <v-col cols="3">
                            <v-btn color="blue darken-1"
                                   text
                                   @click="closeDialog"
                            >
                                Close
                            </v-btn>
                        </v-col>
                        <v-col cols="3">
                            <v-btn color="blue darken-1"
                                   text
                                   @click="saveExperience"
                            >
                                Save
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, minLength, maxLength, between } from 'vuelidate/lib/validators'

export default {
    mixins: [validationMixin],
    name: "ExperienceDialog",
    props: {
        'user_id': {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            dialog: false,

            startDateMenu: false,
            endDateMenu: false,

            // data for date pickers
            currentDate: new Date().toISOString().slice(0,10),

            experience: {
                user_id: this.user_id,
                cv_id: null,
                is_current_job: true,
                start_date: null,
                end_date: null,
                job_title: null,
                company_name: null,
                job_description: null,
            },

            labels: {
                start_date: 'Start of work',
                is_current_job: 'To present',
                end_date: 'End of work',
                company_name: 'Company name',
                job_title: 'Job title',
                job_description: 'Job description'
            }
        }
    },
    validations: {
        experience: {
            start_date: {
                required
            },
            job_title: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            company_name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            job_description: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(4000)
            },
        }
    },
    computed: {
        jobTitleErrors () {
            const errors = [];
            if (!this.$v.experience.job_title.$dirty) return errors;
            !this.$v.experience.job_title.maxLength && errors.push('Job title must be at most 255 characters long');
            !this.$v.experience.job_title.required && errors.push('Job title is required.');
            return errors;
        },
        companyNameErrors () {
            const errors = [];
            if (!this.$v.experience.company_name.$dirty) return errors;
            !this.$v.experience.company_name.maxLength && errors.push('Company name must be at most 255 characters long');
            !this.$v.experience.company_name.required && errors.push('Company name is required.');
            return errors;
        },
        jobDescriptionErrors () {
            const errors = [];
            if (!this.$v.experience.job_description.$dirty) return errors;
            !this.$v.experience.job_description.maxLength && errors.push('Job description must be at most 4000 characters long');
            !this.$v.experience.job_description.required && errors.push('Job description is required.');
            return errors;
        },
        startDateErrors () {
            const errors = [];
            if (!this.$v.experience.start_date.$dirty) return errors;
            !this.$v.experience.start_date.required && errors.push('Start of work is required.');
            return errors;
        }
    },
    methods: {
        clearExperience() {
            for (const property in this.experience) {
                this.experience[property] = null;
            }
            this.experience.user_id = this.user_id;
            this.experience.is_current_job = true;
        },

        closeDialog() {
            this.dialog = false;
            this.clearExperience();
        },

        saveExperience() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR';
            } else {
                // do your submit logic here
                this.submitStatus = 'PENDING';
                this.$v.$reset();

                if (this.experience.is_current_job) {
                    this.experience.end_date = null;
                }
                this.experience.is_current_job = this.experience.is_current_job ? 1 : 0;

                this.$emit('newExperience', JSON.parse(JSON.stringify(this.experience)));

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
                this.closeDialog();
            }
        }
    }
}
</script>

<style scoped>

</style>
