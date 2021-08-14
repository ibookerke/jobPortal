<template>
    <div>
        <v-row v-for="(item, key) in vacancyArray" :key="key">
            <v-col>
                <v-card class="mx-auto" elevation="10" outlined shaped>
                    <v-card-title>
                        <h2>
                            {{ item.job_post.job_title }}
                        </h2>
                    </v-card-title>
                    <v-card-text>
                        <div class="text-subtitle-1">
                            KZT • {{ item.job_post.salary }}
                        </div>
                        <div class="text-subtitle-1">
                            Experience: {{ item.work_experience_type.name }}
                        </div>
                        <div class="text-subtitle-1">
                            Location: {{ item.job_location_details.country.name }}, {{ item.job_location_details.state.name }}, {{ item.job_location_details.city.name }}
                        </div>
                    </v-card-text>

                    <v-divider class="mx-4"></v-divider>

                    <v-card-title>
                        Description
                    </v-card-title>
                    <v-card-text>
                        <div>{{ item.job_post.job_description }}</div>
                    </v-card-text>

                    <v-divider class="mx-10"></v-divider>

                    <v-card-title>
                        Job Types
                    </v-card-title>
                    <v-card-text>
                        <v-chip-group column>
                            <v-chip v-for="(jobType, jobTypeKey) in item.job_type_array" :key="jobTypeKey">{{ jobType.name }}</v-chip>
                        </v-chip-group>
                    </v-card-text>

                    <v-divider class="mx-10"></v-divider>

                    <v-card-title>
                        Skills
                    </v-card-title>
                    <v-card-text>
                        <v-chip-group column>
                            <v-chip v-for="(skill, skillKey) in item.skill_array" :key="skillKey">{{ skill.name }}</v-chip>
                        </v-chip-group>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="primary" @click="editVacancy(key)">
                            EDIT
                        </v-btn>
                        <v-btn color="error" @click="deleteVacancy(item.job_post.id)">
                            DELETE
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "VacancyCards",
    props: ['companyID', 'userID'],
    data() {
        return {
            vacancyArray: [],
            jobPost: {
                job_post: {},
                job_type_array: [],
                job_location: {},
                skill_array: [],
                job_location_details: {},
                work_experience_type: {},
            },
        }
    },
    created() {
        if (this.companyID || this.userID) {
            this.getData();
        }
    },
    methods: {
        getData(companyID, userID) {
            axios.post(
                '/api/get_all_vacancies',
                {
                    'company_id': this.companyID,
                    'user_id': this.userID,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.vacancyArray = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        editVacancy(index) {
            this.$emit('updateJobPost', this.vacancyArray[index]);
            this.$router.push({name: 'edit_job_post'});
        },
        deleteVacancy(job_post_id) {
            this.$emit('deleteJobPost', job_post_id);
        }
    }
}
</script>

<style scoped>

</style>
