<template>
    <div>
        <v-row>
            <v-col>
                <radio-button :label="labels.workExperienceRadio"
                              :radios="workExperienceRadio.radios"
                              v-model="workExperienceRadio.value"
                />
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
                    <experience-dialog :user_id="user_id"
                                       @newExperience="addExperience"
                    />
                </v-col>
            </v-row>
            <experience-cards :experience="experience"
                              @deleteExperience="deleteExperience"
            />
        </div>
    </div>
</template>

<script>
import RadioButton from "../../Inputs/RadioButton";
import ExperienceDialog from "./ExperienceDialog";
import ExperienceCards from "./ExperienceCards";

export default {
    name: "Profession",
    components: {
        ExperienceCards,
        ExperienceDialog,
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
            workExperienceRadio: {
                label: 'Work experience',
                radios: [
                    'No work experience',
                    'Has experience'
                ],
                value: null
            },

            experience: [],

            labels: {
                workExperienceRadio: 'Work experience',
            },
        }
    },
    methods: {
        addExperience(value) {
            this.experience.push(value);
        },

        deleteExperience(value) {
            this.experience.splice(value, 1);
        }
    },
    watch: {
        experience: function () {
            if (this.workExperienceRadio.value) {
                this.$emit('input', this.experience);
            }
        }
    }
}
</script>

<style scoped>

</style>
