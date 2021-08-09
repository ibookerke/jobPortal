<template>
    <div>
        <h1>ВОт тут будет грид(я сам сделаю)</h1>
        <v-btn @click="$router.push({name: 'edit_job_post'})" color="success">
            Создать новую вакансию
        </v-btn>
    </div>
</template>

<script>
export default {
    name: "MyJobPosts"
}
</script>

<style scoped>

</style>
