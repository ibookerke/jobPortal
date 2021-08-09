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
                    v-model.trim="profile.firstname"
                    :label="labels.firstname"
                    outlined

                    :error-messages="firstNameErrors"
                    :counter="255"
                    required
                    @input="$v.profile.firstname.$touch()"
                    @blur="$v.profile.firstname.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="profile.lastname"
                    :label="labels.lastname"
                    outlined

                    :error-messages="lastNameErrors"
                    :counter="255"
                    required
                    @input="$v.profile.lastname.$touch()"
                    @blur="$v.profile.lastname.$touch()"
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
                            v-model="profile.date_of_birth"
                            :label="labels.date_of_birth"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"

                            :error-messages="dateOfBirthErrors"
                            required
                            @input="$v.profile.date_of_birth.$touch()"
                            @blur="$v.profile.date_of_birth.$touch()"
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="profile.date_of_birth"
                        @input="dateOfBirthMenu = false"
                        :max="dateOfBirthMax"
                        elevation="15"
                    ></v-date-picker>
                </v-menu>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-radio-group v-model="profile.gender"

                               :error-messages="genderErrors"
                               required
                               @input="$v.profile.gender.$touch()"
                               @blur="$v.profile.gender.$touch()"
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
                <VuePhoneNumberInput v-model="defaultPhone" @update="updatePhoneNumber" default-country-code="KZ"/>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="profile.job_title"
                    :label="labels.job_title"
                    outlined

                    :error-messages="jobTitleErrors"
                    :counter="255"
                    required
                    @input="$v.profile.job_title.$touch()"
                    @blur="$v.profile.job_title.$touch()"
                />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="profile.salary"
                    :label="labels.salary"
                    type="number"
                    outlined

                    :error-messages="salaryErrors"
                    required
                    @input="$v.profile.salary.$touch()"
                    @blur="$v.profile.salary.$touch()"
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
import { required, minLength, maxLength } from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],
    name: "personalInformation",
    components: {
        VuePhoneNumberInput
    },
    props: [
        'user_id'
    ],
    data() {
        return {
            profile: {
                user_id: null,
                job_title: null,
                firstname: null,
                lastname: null,
                date_of_birth: null,
                gender: null,
                phone: null,
                salary: null,
                currency: null
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

            // default number, since needs format from updatePhoneNumber()
            defaultPhone: null,

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
    validations: {
        profile: {
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
                minLength: minLength(1),
                maxLength: maxLength(255)
            },
        }
    },
    computed: {
        jobTitleErrors () {
            const errors = [];
            if (!this.$v.profile.job_title.$dirty) return errors;
            !this.$v.profile.job_title.maxLength && errors.push(this.labels.job_title + 'must be at most 255 characters long');
            !this.$v.profile.job_title.required && errors.push(this.labels.job_title + ' is required.');
            return errors;
        },
        firstNameErrors () {
            const errors = [];
            if (!this.$v.profile.firstname.$dirty) return errors;
            !this.$v.profile.firstname.maxLength && errors.push(this.labels.firstname + ' must be at most 255 characters long');
            !this.$v.profile.firstname.required && errors.push(this.labels.firstname + ' is required.');
            return errors;
        },
        lastNameErrors () {
            const errors = [];
            if (!this.$v.profile.lastname.$dirty) return errors;
            !this.$v.profile.lastname.maxLength && errors.push(this.labels.lastname + ' must be at most 255 characters long');
            !this.$v.profile.lastname.required && errors.push(this.labels.lastname + ' is required.');
            return errors;
        },
        dateOfBirthErrors () {
            const errors = [];
            if (!this.$v.profile.date_of_birth.$dirty) return errors;
            !this.$v.profile.date_of_birth.required && errors.push(this.labels.date_of_birth + ' is required.');
            return errors;
        },
        genderErrors () {
            const errors = [];
            if (!this.$v.profile.gender.$dirty) return errors;
            !this.$v.profile.gender.required && errors.push(this.labels.gender + ' is required.');
            return errors;
        },
        salaryErrors () {
            const errors = [];
            if (!this.$v.profile.salary.$dirty) return errors;
            !this.$v.profile.salary.maxLength && errors.push(this.labels.salary + 'must be at most 255 characters long');
            return errors;
        },
    },
    methods: {
        // saves needed format of phone number
        updatePhoneNumber(val) {
            this.profile.phone = val.formattedNumber;
        },

        // counts current eligible age for work
        eligibleAgeForWork() {
            let current = new Date();
            let year = current.getFullYear() - 14;
            let month = current.getMonth();
            let day = current.getDate();
            return new Date(year, month, day).toISOString().slice(0,10);
        }
    },
    created() {
        this.profile.user_id = this.user_id;
        this.dateOfBirthMax = this.eligibleAgeForWork();
    },
    watch: {
        profile: {
            handler: function (val) {
                this.$emit('input', val);
            },
            deep: true
        }
    }
}
</script>

<style scoped>

</style>
