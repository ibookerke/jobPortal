<template>
    <div>
        <v-row v-for="(item, index) in experienceArray" :key="index">
            <v-col>
                <v-card outlined>
                    <v-card-title>
                        {{ item.company_name }}
                    </v-card-title>
                    <v-card-subtitle>
                        {{ 'from ' + item.start_date + ' to ' + endDate(item.end_date) }}
                    </v-card-subtitle>
                    <v-card-text>
                        {{ item.job_description }}
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="error"
                            depressed
                            @click="deleteExperience(index)"
                        >
                            <v-icon dark>
                                mdi-delete
                            </v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "ExperienceCards",
    props: ['currentExperienceArray'],
    data() {
        return {
            experienceArray: [],
        };
    },
    created() {
        this.experienceArray = this.currentExperienceArray;
    },
    methods: {
        deleteExperience(index) {
            this.$emit('deleteItemFromExperienceArray', index);
        },
        endDate(date) {
            return (!date) ? 'current day' : date;
        }
    }
}
</script>

<style scoped>

</style>
