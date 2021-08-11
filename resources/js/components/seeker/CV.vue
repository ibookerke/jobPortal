<template>
    <div class="loader" v-if="loading">
        <v-progress-linear
            color="white"
            indeterminate
        ></v-progress-linear>
    </div>
    <v-card v-else class="mx-auto" elevation="10" outlined shaped>
        <v-card-title>
            <v-row>
                <v-col>
                    <h2>
                        {{ item.job_title }}
                    </h2>
                </v-col>
            </v-row>

        </v-card-title>
        <v-container>
            <v-row>
                <v-col>
                    <h2 class="fio">{{item.firstname + " " + item.lastname}}</h2>
                </v-col>
            </v-row>
        </v-container>

        <v-card-text>
            <div class="text-subtitle-1">
                KZT • {{ item.salary }}
            </div>
        </v-card-text>

        <v-divider class="mx-4"></v-divider>

        <v-card-title>
            Work experience
        </v-card-title>
        <v-card-text>
            <v-timeline align-top dense>
                <v-timeline-item v-for="(experience, exKey) in item.experiences" :key="exKey" small>
                    <div>
                        <div class="font-weight-normal">
                            from <strong>{{ experience.start_date }}</strong> to <strong>{{ endDate(experience.is_current_job, experience.end_date) }}</strong>
                        </div>
                        <div>Company: <strong>{{ experience.company_name }}</strong></div>
                        <div>Job title: <strong>{{ experience.job_title }}</strong></div>
                    </div>
                </v-timeline-item>
            </v-timeline>
        </v-card-text>

        <v-divider class="mx-10"></v-divider>

        <v-card-title>
            Education
        </v-card-title>
        <v-card-text>
            <v-timeline align-top dense>
                <v-timeline-item v-for="(education, edKey) in item.educations" :key="edKey" small>
                    <div>
                        <div class="font-weight-normal">
                            from <strong>{{ education.starting_date }}</strong> to <strong>{{ education.completing_date }}</strong>
                        </div>
                        <div>Certificate degree: <strong>{{ education.certificate_degree_name }}</strong></div>
                    </div>
                </v-timeline-item>
            </v-timeline>
        </v-card-text>

        <v-divider class="mx-10"></v-divider>

        <v-card-title>
            Skills
        </v-card-title>
        <v-card-text>
            <v-chip-group column>
                <v-chip v-for="(skill, skillKey) in item.skills" :key="skillKey">{{ skill.name }}</v-chip>
            </v-chip-group>
        </v-card-text>

    </v-card>
</template>

<script>
export default {
    name: "CV",
    data(){
        return {
            record_id: null,
            item: null,
            loading: true
        }
    },
    created() {
        if(this.$store.getters.getRow.id === null || this.$store.getters.getRow.id === undefined){
            this.$router.push("/cvs")
        }
        else{
            this.record_id = this.$route.params.id
            this.item = this.$store.getters.getRow
            this.loading = false

        }
    },
    methods:{
        endDate(icj, date) {
            return (icj) ? 'present' : date;
        }
    }

}
</script>

<style scoped>
.fio{
    text-transform: capitalize;
}
</style>
