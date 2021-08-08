<template>
    <v-form>
        <v-container>
            <v-row>
                <v-col>
                    <h1>Your CV</h1>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <h2>Personal Information</h2>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="First Name"
                        outlined
                        v-model="profileInfo.firstname"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Last Name"
                        outlined
                        v-model="profileInfo.lastname"
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
                                v-model="profileInfo.date_of_birth"
                                label="Date of birth"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="profileInfo.date_of_birth"
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
                            :value="0"
                        ></v-radio>
                        <v-radio
                            label="Female"
                            :value="1"
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
                    <v-radio-group v-model="workExperience.hasExperienceRadio">
                        <template v-slot:label>
                            <div>Work experience</div>
                        </template>
                        <v-radio
                            label="No work experience"
                            :value="0"
                        ></v-radio>
                        <v-radio
                            label="Has experience"
                            :value="1"
                        ></v-radio>
                    </v-radio-group>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <h2>Profession</h2>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Job Title"
                        outlined
                        v-model="profileInfo.job_title"
                    ></v-text-field>
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
            <div v-if="workExperience.hasExperienceRadio === 1">
                <v-row>
                    <v-col>
                        <h2>Work experience</h2>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-label>Places of work</v-label>
                    </v-col>
                    <v-col>
                        <v-dialog
                            v-model="workExperience.dialog"
                            persistent
                            max-width="600px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    color="primary"
                                    dark
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    Add work
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">Work experience</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col>
                                                <v-menu
                                                    v-model="workExperience.startDate.menu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    required
                                                    min-width="auto"
                                                >
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            v-model="workExperience.startDate.defaultValue"
                                                            label="Start of work"
                                                            prepend-icon="mdi-calendar"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="workExperience.startDate.defaultValue"
                                                        @input="workExperience.startDate.menu = false"
                                                        elevation="15"
                                                        :max="currentDate"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col cols="3">
                                                <v-checkbox
                                                    v-model="workExperience.endDate.checkbox"
                                                    label="To present"
                                                ></v-checkbox>
                                            </v-col>
                                            <v-col cols="9" v-if="workExperience.endDate.checkbox === false">
                                                <v-menu
                                                    v-model="workExperience.endDate.menu"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="auto"
                                                >
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            v-model="workExperience.endDate.defaultValue"
                                                            label="End of work"
                                                            prepend-icon="mdi-calendar"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="workExperience.endDate.defaultValue"
                                                        @input="workExperience.endDate.menu = false"
                                                        elevation="15"
                                                        :min="workExperience.startDate.defaultValue"
                                                        :max="currentDate"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-text-field
                                                    label="Organization"
                                                    outlined
                                                    required
                                                    v-model="workExperience.defaultCompanyName"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-text-field
                                                    label="Job Title"
                                                    outlined
                                                    required
                                                    v-model="workExperience.defaultJobTitle"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col>
                                                <v-text-field
                                                    label="Job Description"
                                                    outlined
                                                    required
                                                    v-model="workExperience.defaultJobDescription"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="closeWorkExperienceDialog"
                                    >
                                        Close
                                    </v-btn>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="saveWorkExperienceDialog"
                                    >
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-col>

                </v-row>
                <v-row v-for="(item, index) in workExperience.array" :key="index">
                    <v-col>
                        <v-card outlined>
                            <v-card-title>
                                {{ item.company_name }}
                            </v-card-title>
                            <v-card-subtitle>
                                {{ 'from ' + item.start_date + ' to ' + item.endDate }}
                            </v-card-subtitle>
                            <v-card-text>
                                {{ item.job_description }}
                            </v-card-text>
                            <v-card-actions>
                                <v-btn
                                    color="error"
                                    depressed
                                    @click="deleteWorkExperience(index)"
                                >
                                    <v-icon dark>
                                        mdi-delete
                                    </v-icon>
                                </v-btn>
                                <v-btn
                                    color="primary"
                                    depressed
                                >
                                    <v-icon dark>
                                        mdi-pencil
                                    </v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </div>
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
            // information from users table
            user: {},

            // information from users cvs
            profileInfo: {
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

            // data for date pickers
            currentDate: new Date().toISOString().slice(0,10),

            // data needed for date of birth field
            dateOfBirth: {
                menu: false,
                max: null
            },

            // default v-model for phone field
            defaultNumber: null,

            // work experience
            workExperience: {
                hasExperienceRadio: null,
                dialog: false,
                startDate: {
                    menu: false,
                    defaultValue: null
                },
                endDate: {
                    checkbox: true,
                    menu: false,
                    defaultValue: null
                },
                defaultJobTitle: null,
                defaultCompanyName: null,
                defaultJobDescription: null,
                array: [],
            },

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
        closeWorkExperienceDialog() {
            this.workExperience.dialog = false;
            this.workExperience.startDate.defaultValue = null;
            this.workExperience.endDate.defaultValue = null;
            this.workExperience.endDate.checkbox = true;
            this.workExperience.defaultJobTitle = null;
            this.workExperience.defaultJobDescription = null;
            this.workExperience.defaultCompanyName = null;
        },
        saveWorkExperienceDialog() {
            let workExperience = {};
            workExperience.user_id = this.user.id;
            workExperience.cv_id = null;
            workExperience.is_current_job = this.workExperience.endDate.checkbox;
            workExperience.start_date = this.workExperience.startDate.defaultValue;
            workExperience.end_date = this.workExperience.endDate.defaultValue;
            workExperience.job_title = this.workExperience.defaultJobTitle;
            workExperience.company_name = this.workExperience.defaultCompanyName;
            workExperience.job_description = this.workExperience.defaultJobDescription;
            this.workExperience.array[this.workExperience.array.length] = workExperience;
            console.log(this.workExperience.array);
            this.closeWorkExperienceDialog();
        },
        deleteWorkExperience(index) {
            this.workExperience.array.splice(index, 1);
        },
        saveForm() {
            console.log(this.profileInfo)
        },
    },
    created() {
        this.user = this.user_info;
        this.dateOfBirth.max = this.eligibleDateOfBirth();
    },
    watch: {
        user_info() {
            this.user = this.user_info;
            this.profileInfo.user_id = this.user.id;
        }
    }
}
</script>

<style scoped>

</style>
