<template>
    <v-container style="margin-bottom: 140px">
        <h1>ВОт тут будет грид(я сам сделаю)</h1>
        <v-row>
            <v-col>
                <v-btn @click="createJobPost" color="success">
                    Создать новую вакансию
                </v-btn>
            </v-col>
        </v-row>
        <vacancy-cards/>
    </v-container>
</template>

<script>
import VacancyCards from "./VacancyCards";
export default {
    name: "MyJobPosts",
    components: {VacancyCards},
    methods: {
        createJobPost() {
            this.$store.commit('setJPEditTypeToCreate');
            this.$router.push({name: 'edit_job_post'});
        }
    }
}
</script>

<style scoped>

</style>
