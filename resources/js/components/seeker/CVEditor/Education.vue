<template>
    <div>
        <v-row>
            <v-col>
                <radio-button :label="labels.educationRadio"
                              :radios="educationRadio.radios"
                              v-model="educationRadio.value"
                />
            </v-col>
        </v-row>
        <div v-if="educationRadio.value">
            <v-row>
                <v-col>
                    <h2>Education</h2>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-label>Places of work</v-label>
                </v-col>
                <v-col>
                    <education-dialog :user_id="user_id"
                                       @newEducation="addEducation"
                    />
                </v-col>
            </v-row>
            <education-cards :education="education"
                             @deleteEducation="deleteEducation"/>
        </div>
    </div>
</template>

<script>
import RadioButton from "../../Inputs/RadioButton";
import EducationDialog from "./EducationDialog";
import EducationCards from "./EducationCards";

export default {
    name: "Education",
    components: {
        EducationCards,
        EducationDialog,
        RadioButton
    },
    props: {
        'user_id': {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            educationRadio: {
                label: 'Education',
                radios: [
                    'No education',
                    'Has education'
                ],
                value: null
            },

            education: [],

            labels: {
                educationRadio: 'Work education',
            },
        }
    },
    methods: {
        addEducation(value) {
            this.education.push(value);
        },

        deleteEducation(value) {
            this.education.splice(value, 1);
        },


    },
    watch: {
        education: function () {
            if (this.educationRadio.value) {
                this.$emit('input', this.education);
            }
        }
    }
}
</script>

<style scoped>

</style>
