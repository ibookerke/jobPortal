<template>
    <v-form>
        <v-container>
            <v-row>
                <h1>Your CV</h1>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Job Title"
                        outlined
                        v-model="profileInfo.jobTitle"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="First Name"
                        outlined
                        v-model="profileInfo.firstName"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Last Name"
                        outlined
                        v-model="profileInfo.lastName"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-menu
                        v-model="dateOfBirth.menu"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="profileInfo.dateOfBirth"
                                label="Date of birth"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="profileInfo.dateOfBirth"
                            @input="dateOfBirth.menu = false"
                            :max="dateOfBirth.max"
                            elevation="15"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-radio-group v-model="profileInfo.gender">
                        <template v-slot:label>
                            <div>Gender</div>
                        </template>
                        <v-radio
                            label="Male"
                            value="0"
                        ></v-radio>
                        <v-radio
                            label="Female"
                            value="1"
                        ></v-radio>
                    </v-radio-group>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <VuePhoneNumberInput v-model="defaultNumber" @update="updatePhoneNumber" default-country-code="KZ"/>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        type="number"
                        label="Desired salary"
                        outlined
                        v-model="profileInfo.salary"
                        prefix="$"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-btn
                        rounded
                        color="primary"
                        dark
                        @click="saveForm"
                    >
                        Create CV
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
// component for phone number input
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
    name: "SaveCV",
    components: {
        VuePhoneNumberInput
    },
    data() {
        return {
            user: {},
            profileInfo: {
                jobTitle: null,
                firstName: null,
                lastName: null,
                dateOfBirth: null,
                gender: null,
                phone: null,
                salary: null,
                currency: null
            },
            dateOfBirth: {
                menu: false,
                max: null
            },
            gender: null,
            defaultNumber: null
        }
    },
    props: [
        "user_info"
    ],
    methods: {
        eligibleDateOfBirth() {
            let current = new Date();
            let year = current.getFullYear() - 14;
            let month = current.getMonth();
            let day = current.getDate();
            return new Date(year, month, day).toISOString().slice(0,10);
        },
        updatePhoneNumber(val) {
            this.profileInfo.phone = val.formattedNumber;
        },
        saveForm() {
            console.log(this.profileInfo)
        }
    },
    created() {
        this.user = this.user_info;
        this.dateOfBirth.max = this.eligibleDateOfBirth();
    },
    watch: {
        user_info() {
            this.user = this.user_info;
        }
    }
}
</script>

<style scoped>

</style>
