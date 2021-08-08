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
            <v-form
                ref="form"
                lazy-validation
            >
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
                                        :rules="rules.start_date"
                                        required
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
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
                                        v-model="experience.end_date"
                                        :label="labels.end_date"
                                        required
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="experience.end_date"
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
                                v-model="experience.company_name"
                                :label="labels.company_name"
                                :rules="rules.company_name"
                                outlined
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="experience.job_title"
                                :label="labels.job_title"
                                :rules="rules.job_title"
                                outlined
                                required
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-textarea
                                v-model="experience.job_description"
                                :label="labels.job_description"
                                :rules="rules.job_description"
                                outlined
                                required
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
import DateField from "../../Inputs/DateField";
import Checkbox from "../../Inputs/Checkbox";
import TextField from "../../Inputs/TextField";

export default {
    name: "ExperienceDialog",
    components: {
        TextField,
        Checkbox,
        DateField
    },
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
            },

            rules: {
                start_date: [
                    v => !!v || this.labels.start_date + ' is required'
                ],
                company_name: [
                    v => !!v || this.labels.company_name + ' is required',
                    v => (v && v.length <= 255) || this.labels.company_name + ' must be less than 255 characters',
                ],
                job_title: [
                    v => !!v || this.labels.job_title + ' is required',
                    v => (v && v.length <= 255) || this.labels.job_title + ' must be less than 255 characters',
                ],
                job_description: [
                    v => !!v || this.labels.job_description + ' is required',
                    v => (v && v.length <= 4000) || this.labels.job_description + ' must be less than 4000 characters',
                ],
            }
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
            if (this.$refs.form.validate())
            {
                this.$emit('newExperience', JSON.parse(JSON.stringify(this.experience)));
                this.$refs.form.reset();
                this.closeDialog();
            }
        }
    }
}
</script>

<style scoped>

</style>
