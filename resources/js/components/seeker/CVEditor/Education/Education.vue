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
                    <education-dialog @addNewEducation="addNewEducation"/>
                </v-col>
            </v-row>
            <education-cards :currentEducationArray="educationArray" @deleteItemFromEducationArray="deleteItemFromEducationArray"/>
        </div>
    </div>
</template>

<script>
import EducationDialog from "./EducationDialog";
import EducationCards from "./EducationCards";

export default {
    name: "Education",
    components: {EducationCards, EducationDialog},
    props: ['editorMode', 'currentEducationArray'],
    data() {
        return {
            educationArray: [],
            educationRadio: {
                label: 'Education',
                radios: [
                    'No education',
                    'Has education'
                ],
                value: 0
            },
            labels: {
                educationRadio: 'Education',
            },
        }
    },
    created() {
        if (this.editorMode === 1)
        {
            this.educationArray = this.currentEducationArray;
            this.educationRadio.value = this.educationArray.length ? 1 : 0;
        }
    },
    watch: {
        'educationRadio.value': function (val) {
            let emit = val ? this.educationArray : [];
            this.$emit('setEducation', emit);
        },
        educationArray: function (val) {
            let emit = this.educationRadio.value ? val : [];
            this.$emit('setEducation', emit);
        },
    },
    methods: {
        addNewEducation(val) {
            this.educationArray.push(val);
        },
        deleteItemFromEducationArray(val) {
            this.educationArray.splice(val, 1);
        }
    }
}
</script>

<style scoped>

</style>
