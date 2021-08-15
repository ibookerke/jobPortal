<template>
    <div>
        <v-row v-for="(item, index) in educationArray" :key="index">
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
    props: ['currentEducationArray'],
    data() {
        return {
            educationArray: [],
        };
    },
    created() {
        this.educationArray = this.currentEducationArray;
    },
    methods: {
        deleteEducation(index) {
            this.$emit('deleteItemFromEducationArray', index);
        }
    }
}
</script>

<style scoped>

</style>
