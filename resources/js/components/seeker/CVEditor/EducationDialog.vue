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
                Add education
            </v-btn>
        </template>
        <v-card>
            <v-form
                ref="educationDialogForm"
                lazy-validation
            >
                <v-container>
                    <v-row>
                        <v-col>
                            <v-menu
                                v-model="startingDateMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="education.starting_date"
                                        :label="labels.starting_date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"

                                        :error-messages="startingDateErrors"
                                        required
                                        @input="$v.education.starting_date.$touch()"
                                        @blur="$v.education.starting_date.$touch()"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="education.starting_date"
                                    @input="startingDateMenu = false"
                                    elevation="15"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-menu
                                v-model="completingDateMenu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="education.completing_date"
                                        :label="labels.completing_date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"

                                        :error-messages="completingDateErrors"
                                        required
                                        @input="$v.education.completing_date.$touch()"
                                        @blur="$v.education.completing_date.$touch()"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="education.completing_date"
                                    @input="completingDateMenu = false"
                                    :min="education.starting_date"
                                    elevation="15"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="education.certificate_degree_name"
                                :label="labels.certificate_degree_name"
                                outlined

                                :error-messages="certificateDegreeNameErrors"
                                required
                                @input="$v.education.certificate_degree_name.$touch()"
                                @blur="$v.education.certificate_degree_name.$touch()"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="education.major"
                                :label="labels.major"
                                outlined

                                :error-messages="majorErrors"
                                required
                                @input="$v.education.major.$touch()"
                                @blur="$v.education.major.$touch()"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="education.percentage"
                                :label="labels.percentage"
                                outlined

                                :error-messages="percentageErrors"
                                required
                                @input="$v.education.percentage.$touch()"
                                @blur="$v.education.percentage.$touch()"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="education.cgpa"
                                :label="labels.cgpa"
                                outlined

                                :error-messages="cgpaErrors"
                                required
                                @input="$v.education.cgpa.$touch()"
                                @blur="$v.education.cgpa.$touch()"
                            ></v-text-field>
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
                                   @click="saveEducation"
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
import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    mixins: [validationMixin],
    name: "EducationDialog",
    props: {
        'user_id': {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            dialog: false,

            startingDateMenu: false,
            completingDateMenu: false,

            education: {
                user_id: this.user_id,
                cv_id: null,
                certificate_degree_name: null,
                major: null,
                starting_date: null,
                completing_date: null,
                percentage: null,
                cgpa: null,
            },

            labels: {
                certificate_degree_name: 'Certificate degree name',
                major: 'Major',
                starting_date: 'Starting date',
                completing_date: 'Completing date',
                percentage: 'Percentage',
                cgpa: 'CGPA'
            }
        }
    },
    validations: {
        education: {
            starting_date: {
                required
            },
            completing_date: {
                required
            },
            certificate_degree_name: {
                required,
                maxLength: maxLength(255)
            },
            major: {
                maxLength: maxLength(255)
            },
            percentage: {
                maxLength: maxLength(255)
            },
            cgpa: {
                maxLength: maxLength(255)
            }
        }
    },
    computed: {
        startingDateErrors () {
            const errors = [];
            if (!this.$v.education.starting_date.$dirty) return errors;
            !this.$v.education.starting_date.required && errors.push(this.labels.starting_date + ' is required.');
            return errors;
        },
        completingDateErrors () {
            const errors = [];
            if (!this.$v.education.completing_date.$dirty) return errors;
            !this.$v.education.completing_date.required && errors.push(this.labels.completing_date + ' is required.');
            return errors;
        },
        certificateDegreeNameErrors () {
            const errors = [];
            if (!this.$v.education.certificate_degree_name.$dirty) return errors;
            !this.$v.education.certificate_degree_name.maxLength && errors.push(this.labels.certificate_degree_name + ' must be at most 255 characters long');
            !this.$v.education.certificate_degree_name.required && errors.push(this.labels.certificate_degree_name + ' is required.');
            return errors;
        },
        majorErrors () {
            const errors = [];
            if (!this.$v.education.major.$dirty) return errors;
            !this.$v.education.major.maxLength && errors.push(this.labels.major + ' must be at most 255 characters long');
            return errors;
        },
        percentageErrors () {
            const errors = [];
            if (!this.$v.education.percentage.$dirty) return errors;
            !this.$v.education.percentage.maxLength && errors.push(this.labels.percentage + ' must be at most 255 characters long');
            return errors;
        },
        cgpaErrors () {
            const errors = [];
            if (!this.$v.education.cgpa.$dirty) return errors;
            !this.$v.education.cgpa.maxLength && errors.push(this.labels.cgpa + ' must be at most 255 characters long');
            return errors;
        }
    },
    methods: {
        clearEducation() {
            for (const property in this.education) {
                this.education[property] = null;
            }
            this.education.user_id = this.user_id;
        },

        closeDialog() {
            this.dialog = false;
            this.clearEducation();
        },

        saveEducation() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR';
            } else {
                // do your submit logic here
                this.submitStatus = 'PENDING';
                this.$v.$reset();

                this.$emit('newEducation', JSON.parse(JSON.stringify(this.education)));

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
