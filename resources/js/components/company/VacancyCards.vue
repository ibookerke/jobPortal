<template>
    <div>
        <v-row v-for="(item, key) in vacancyArray" :key="key">
            <v-col>
                <v-card class="mx-auto" elevation="10" outlined shaped>
                    <v-card-title>
                        <h2>
                            {{ item.jobPost.job_title }}
                        </h2>
                    </v-card-title>
                    <v-card-text>
                        <div class="text-subtitle-1">
                            KZT • {{ item.jobPost.salary }}
                        </div>
                        <div class="text-subtitle-1">
                            Experience: {{ item.workExperienceType.name }}
                        </div>
                        <div class="text-subtitle-1">
                            Location: {{ item.jobLocationDetails.country.name }}, {{ item.jobLocationDetails.state.name }}, {{ item.jobLocationDetails.city.name }}
                        </div>
                    </v-card-text>

                    <v-divider class="mx-4"></v-divider>

                    <v-card-title>
                        Description
                    </v-card-title>
                    <v-card-text>
                        <div>{{ item.jobPost.job_description }}</div>
                    </v-card-text>

                    <v-divider class="mx-10"></v-divider>

                    <v-card-title>
                        Job Types
                    </v-card-title>
                    <v-card-text>
                        <v-chip-group column>
                            <v-chip v-for="(jobType, jobTypeKey) in item.jobTypes" :key="jobTypeKey">{{ jobType.name }}</v-chip>
                        </v-chip-group>
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

                    <v-card-actions>
                        <v-btn color="primary" @click="editVacancy(key)">
                            EDIT
                        </v-btn>
                        <v-btn color="error" @click="deleteVacancy(item.jobPost.id)">
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
    data() {
        return {
            vacancyArray: [],
            company: null,
            user: null
        }
    },
    created() {
        this.user = this.$store.getters.getUserData;
        this.unwatch = this.$store.watch(
            (state, getters) => getters.getCompanyLoadStatus,
            (newValue, oldValue) => {
                if(newValue) {
                    this.company = this.$store.getters.getCompanyData
                    this.getData(this.company.id, this.user.id);
                }
            }
        )
    },
    methods: {
        getData(companyID, userID) {
            axios.post(
                '/api/get_all_vacancies',
                {
                    'company_id': companyID,
                    'user_id': userID
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.vacancyArray = response.data;
                console.log(this.vacancyArray)
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        editVacancy(index) {
            console.log('edit')
            this.$store.commit('setJPEditTypeToUpdate');
            this.$store.commit('setJPPostID', this.vacancyArray[index].jobPost['id']);
            this.$store.commit('setJPJobPostForUpdate', this.vacancyArray[index].jobPost);
            this.$store.commit('setJPJobLocationForUpdate', this.vacancyArray[index].jobLocation);
            this.$store.commit('setJPJobTypes', this.vacancyArray[index].jobTypes);
            this.$store.commit('setJPSkills', this.vacancyArray[index].skills);
            this.$store.commit('setJPWorkExperience', this.vacancyArray[index].workExperienceType);
            this.$store.commit('setJPLocationDetails', this.vacancyArray[index].jobLocationDetails);
            this.$router.push({name: 'edit_job_post'});
        },
        deleteVacancy() {
            console.log('delete')
        }
    }
}
</script>

<style scoped>

</style>
