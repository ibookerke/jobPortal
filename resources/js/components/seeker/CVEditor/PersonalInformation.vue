<template>
    <div>
        <v-row>
            <v-col>
                <h2>Personal Information</h2>
            </v-col>
        </v-row>
        <text-field :row="true" :label="labels.firstname" v-model="profile.firstname"></text-field>
        <text-field :row="true" :label="labels.lastname" v-model="profile.lastname"></text-field>
        <text-field :row="true" :label="labels.job_title" v-model="profile.job_title"></text-field>
        <v-row>
            <v-col>
                <date-field :label="labels.date_of_birth" v-model="profile.date_of_birth" :max="dateOfBirthMax"></date-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <radio-button :label="labels.gender" :radios="genderRadios" v-model="profile.gender"></radio-button>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <VuePhoneNumberInput v-model="defaultPhone" @update="updatePhoneNumber" default-country-code="KZ"/>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import TextField from "../../Inputs/TextField";

// component for phone number input
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import DateField from "../../Inputs/DateField";
import RadioButton from "../../Inputs/RadioButton";

export default {
    name: "personalInformation",
    components: {
        RadioButton,
        DateField,
        TextField,
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

            genderRadios: [
                'Male',
                'Female'
            ]
        }
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
                console.log(val);
                this.$emit('input', val);
            },
            deep: true
        }
    }
}
</script>

<style scoped>

</style>
