<template>
    <v-progress-circular
        :size="70"
        :width="7"
        color="deep-purple"
        indeterminate
    ></v-progress-circular>
</template>

<script>
export default {
    name: "Loading"
}
</script>

<style scoped>
.v-progress-circular {
    margin: 1rem;
}
</style>
