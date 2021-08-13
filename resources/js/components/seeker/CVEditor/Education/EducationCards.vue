<template>
    <div>
        <v-row v-for="(item, index) in education" :key="index">
            <v-col>
                <v-card outlined>
                    <v-card-title>
                        {{ item.certificate_degree_name }}
                    </v-card-title>
                    <v-card-subtitle>
                        {{ 'from ' + item.starting_date + ' to ' + item.completing_date }}
                    </v-card-subtitle>
                    <v-card-text>
                        {{ item.cgpa }}
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="error"
                            depressed
                            @click="deleteEducation(index)"
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
    name: "EducationCards",
    data() {
        return {
            education: null
        }
    },
    created() {
        if (this.$store.getters.getCVEditType) {
            this.education = this.$store.getters.getCVEducation;
        }
        this.unwatch = this.$store.watch(
            (state, getters) => getters.getCVEducation,
            (newValue, oldValue) => {
                this.education = newValue;
            }
        )
    },
    beforeDestroy() {
        this.unwatch();
    },
    methods: {
        deleteEducation(index) {
            if (this.$store.getters.getCVEditType && this.education[index].hasOwnProperty('id'))
            {
                this.$store.commit('updateRemovedEd', this.education[index].id);
            }
            this.$store.commit('cvRemoveEducation', index);
        }
    }
}
</script>

<style scoped>

</style>
