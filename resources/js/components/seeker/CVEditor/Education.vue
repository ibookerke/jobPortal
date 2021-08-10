<template>
    <div>
        <v-row>
            <v-col>
                <v-radio-group v-model="educationRadio.value"
                >
                    <template>
                        <div>{{ labels.educationRadio }}</div>
                    </template>
                    <v-radio v-for="(item, key) in educationRadio.radios"
                             :label="item"
                             :value="key"
                             :key="key"
                    ></v-radio>
                </v-radio-group>
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
                    <education-dialog/>
                </v-col>
            </v-row>
            <education-cards/>
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

            labels: {
                educationRadio: 'Work education',
            },
        }
    },
    created() {
        let get = this.$store.getters;
        if (get.getCVEditType)
        {
            this.educationRadio.value = get.getCVEducation.length ? 1 : 0;
        }
    },
    watch: {
        'educationRadio.value': function (val) {
            this.$store.commit('setCVEducationRadio', val);
        }
    }
}
</script>

<style scoped>

</style>
