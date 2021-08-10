<template>
    <div>
        <v-row v-for="(item, index) in experience" :key="index">
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
    data() {
        return {
            experience: null
        }
    },
    created() {
        if (this.$store.getters.getCVEditType) {
            this.experience = this.$store.getters.getCVExperience;
        }
        this.unwatch = this.$store.watch(
            (state, getters) => getters.getCVExperience,
            (newValue, oldValue) => {
                this.experience = newValue;
            }
        )
    },
    beforeDestroy() {
        this.unwatch();
    },
    methods: {
        deleteExperience(index) {
            if (this.$store.getters.getCVEditType && this.experience[index].hasOwnProperty('id'))
            {
                this.$store.commit('updateRemovedExp', this.experience[index].id);
            }
            this.$store.commit('cvRemoveExperience', index);
        },

        endDate(date) {
            return (!date) ? 'current day' : date;
        }
    }
}
</script>

<style scoped>

</style>
