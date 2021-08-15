<template>
    <div>
        <v-row>
            <v-col>
                <v-radio-group v-model="workExperienceRadio.value"
                >
                    <template>
                        <div>{{ labels.workExperienceRadio }}</div>
                    </template>
                    <v-radio v-for="(item, key) in workExperienceRadio.radios"
                             :label="item"
                             :value="key"
                             :key="key"
                    ></v-radio>
                </v-radio-group>
            </v-col>
        </v-row>
        <div v-if="workExperienceRadio.value">
            <v-row>
                <v-col>
                    <h2>Experience</h2>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-label>Places of work</v-label>
                </v-col>
                <v-col>
                    <experience-dialog @addNewExperience="addNewExperience"/>
                </v-col>
            </v-row>
            <experience-cards :currentExperienceArray="experienceArray" @deleteItemFromExperienceArray="deleteItemFromExperienceArray"/>
        </div>
    </div>
</template>

<script>
import ExperienceDialog from "./ExperienceDialog";
import ExperienceCards from "./ExperienceCards";

export default {
    name: "Profession",
    components: {ExperienceCards, ExperienceDialog},
    props: ['editorMode', 'currentExperienceArray'],
    data() {
        return {
            experienceArray: [],
            workExperienceRadio: {
                label: 'Work experience',
                radios: [
                    'No work experience',
                    'Has experience'
                ],
                value: 0
            },
            labels: {
                workExperienceRadio: 'Work experience',
            },
        }
    },
    created() {
        if (this.editorMode === 1)
        {
            this.experienceArray = this.currentExperienceArray;
            this.workExperienceRadio.value = this.experienceArray.length ? 1 : 0;
        }
    },
    watch: {
        'workExperienceRadio.value': function (val) {
            let emit = val ? this.experienceArray : [];
            this.$emit('setExperience', emit);
        },
        educationArray: function (val) {
            let emit = this.workExperienceRadio.value ? val : [];
            this.$emit('setExperience', emit);
        },
    },
    methods: {
        addNewExperience(val) {
            this.experienceArray.push(val);
        },
        deleteItemFromExperienceArray(val) {
            this.experienceArray.splice(val, 1);
        }
    }
}
</script>

<style scoped>

</style>
