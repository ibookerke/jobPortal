<template>
    <v-form ref="job_form" style="margin-bottom: 140px">
        <v-container>
            <v-row>
                <v-col>
                    <h1>
                        Vacancy Example
                    </h1>
                </v-col>
            </v-row>
            <job-type-select/>
            <job-skill-set/>
            <job-location/>
        </v-container>
    </v-form>
</template>

<script>
import JobTypeSelect from "./job/JobTypeSelect";
import JobLocation from "./job/JobLocation";
import JobSkillSet from "./job/JobSkillSet";
export default {
name: "JobPostEditor",
    components: {JobSkillSet, JobLocation, JobTypeSelect},
    created() {
        let store = this.$store;
    }
}
</script>

<style scoped>

</style>
