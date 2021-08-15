<template>
    <div>
        <v-row>
            <v-col>
                <h2>Personal Information</h2>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="personalInformation.firstname"
                    :label="labels.firstname"
                    outlined

                    :error-messages="firstNameErrors"
                    :counter="255"
                    required
                    @input="$v.personalInformation.firstname.$touch()"
                    @blur="$v.personalInformation.firstname.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="personalInformation.lastname"
                    :label="labels.lastname"
                    outlined

                    :error-messages="lastNameErrors"
                    :counter="255"
                    required
                    @input="$v.personalInformation.lastname.$touch()"
                    @blur="$v.personalInformation.lastname.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-menu
                    v-model="dateOfBirthMenu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="personalInformation.date_of_birth"
                            :label="labels.date_of_birth"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"

                            :error-messages="dateOfBirthErrors"
                            required
                            @input="$v.personalInformation.date_of_birth.$touch()"
                            @blur="$v.personalInformation.date_of_birth.$touch()"
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="personalInformation.date_of_birth"
                        @input="dateOfBirthMenu = false"
                        :max="dateOfBirthMax"
                        elevation="15"
                    ></v-date-picker>
                </v-menu>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-radio-group v-model="personalInformation.gender"

                               :error-messages="genderErrors"
                               required
                               @input="$v.personalInformation.gender.$touch()"
                               @blur="$v.personalInformation.gender.$touch()"
                >
                    <template>
                        <div>{{ labels.gender }}</div>
                    </template>
                    <v-radio v-for="(item, key) in genderRadios"
                             :label="item"
                             :value="key"
                             :key="key"
                    ></v-radio>
                </v-radio-group>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
<!--                @update="updatePhoneNumber"-->
<!--                v-model="defaultPhone"-->
                <VuePhoneNumberInput v-model="personalInformation.phone" default-country-code="KZ"/>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="personalInformation.job_title"
                    :label="labels.job_title"
                    outlined

                    :error-messages="jobTitleErrors"
                    :counter="255"
                    required
                    @input="$v.personalInformation.job_title.$touch()"
                    @blur="$v.personalInformation.job_title.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="personalInformation.salary"
                    :label="labels.salary"
                    type="number"
                    outlined
                    prefix="KZT"

                    :error-messages="salaryErrors"
                    required
                    @input="$v.personalInformation.salary.$touch()"
                    @blur="$v.personalInformation.salary.$touch()"
                />
            </v-col>
        </v-row>
    </div>
</template>

<script>
// component for phone number input
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

// validation
import { validationMixin } from 'vuelidate';
import {required, minLength, maxLength, integer, minValue, maxValue} from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],
    name: "personalInformation",
    components: {VuePhoneNumberInput},
    props: ['editorMode', 'currentPersonalInformation'],
    data() {
        return {
            personalInformation: {
                user_id: this.$store.getters.getSeekerUserID,
                job_title: null,
                firstname: null,
                lastname: null,
                date_of_birth: null,
                gender: null,
                phone: null,
                salary: null
            },
            labels: {
                job_title: 'Job Title',
                firstname: 'First Name',
                lastname: 'Last Name',
                date_of_birth: 'Date of birth',
                gender: 'Gender',
                phone: 'Phone',
                salary: 'Salary',
                currency: 'Currency'
            },

            // current eligible age for work (14 years +)
            dateOfBirthMax: null,
            dateOfBirthMenu: false,

            genderRadios: [
                'Male',
                'Female',
                'Prefer not to choose'
            ]
        }
    },
    created() {
        if (this.editorMode === 1) { // update mode
            this.personalInformation = this.currentPersonalInformation;
        }
        this.dateOfBirthMax = this.eligibleAgeForWork();
    },
    watch: {
        personalInformation: {
            handler: function (val) {
                this.$emit('setPersonalInformation', val);
            },
            deep: true
        }
    },
    methods: {
        // counts current eligible age for work
        eligibleAgeForWork() {
            let current = new Date();
            let year = current.getFullYear() - 14;
            let month = current.getMonth();
            let day = current.getDate();
            return new Date(year, month, day).toISOString().slice(0,10);
        }
    },
    validations: {
        personalInformation: {
            firstname: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            lastname: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            date_of_birth: {
                required
            },
            gender: {
                required
            },
            job_title: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            salary: {
                integer,
                minValue: minValue(0),
                maxValue: maxValue(2147483647)
            },
        }
    },
    computed: {
        jobTitleErrors () {
            const errors = [];
            if (!this.$v.personalInformation.job_title.$dirty) return errors;
            !this.$v.personalInformation.job_title.maxLength && errors.push(this.labels.job_title + 'must be at most 255 characters long');
            !this.$v.personalInformation.job_title.required && errors.push(this.labels.job_title + ' is required.');
            return errors;
        },
        firstNameErrors () {
            const errors = [];
            if (!this.$v.personalInformation.firstname.$dirty) return errors;
            !this.$v.personalInformation.firstname.maxLength && errors.push(this.labels.firstname + ' must be at most 255 characters long');
            !this.$v.personalInformation.firstname.required && errors.push(this.labels.firstname + ' is required.');
            return errors;
        },
        lastNameErrors () {
            const errors = [];
            if (!this.$v.personalInformation.lastname.$dirty) return errors;
            !this.$v.personalInformation.lastname.maxLength && errors.push(this.labels.lastname + ' must be at most 255 characters long');
            !this.$v.personalInformation.lastname.required && errors.push(this.labels.lastname + ' is required.');
            return errors;
        },
        dateOfBirthErrors () {
            const errors = [];
            if (!this.$v.personalInformation.date_of_birth.$dirty) return errors;
            !this.$v.personalInformation.date_of_birth.required && errors.push(this.labels.date_of_birth + ' is required.');
            return errors;
        },
        genderErrors () {
            const errors = [];
            if (!this.$v.personalInformation.gender.$dirty) return errors;
            !this.$v.personalInformation.gender.required && errors.push(this.labels.gender + ' is required.');
            return errors;
        },
        salaryErrors () {
            const errors = [];
            if (!this.$v.personalInformation.salary.$dirty) return errors;
            !this.$v.personalInformation.salary.minValue && errors.push('Salary must be positive');
            !this.$v.personalInformation.salary.maxValue && errors.push('Salary must be lower than 2147483647');
            !this.$v.personalInformation.salary.integer && errors.push('Salary must be an integer');
            return errors;
        },
    },
}
</script>

<style scoped>

</style>
