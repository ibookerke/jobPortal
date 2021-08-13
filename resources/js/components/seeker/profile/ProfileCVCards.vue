<template>
    <div>
        <v-row v-if="cvs.length" v-for="(item, key) in cvs" :key="key">
            <v-col>
                <v-card class="mx-auto" elevation="10" outlined shaped>
                    <v-card-title>
                        <h2>
                            {{ item.cv.job_title }}
                        </h2>
                    </v-card-title>
                    <v-card-text v-if="item.cv.salary">
                        <div class="text-subtitle-1">
                            $ • {{ item.cv.salary }}
                        </div>
                    </v-card-text>

                    <div v-if="item.experienceArray.length">
                        <v-divider class="mx-4"/>
                        <v-card-title>
                            Work experience
                        </v-card-title>
                        <v-card-text>
                            <v-timeline align-top dense>
                                <v-timeline-item v-for="(experience, exKey) in item.experienceArray" :key="exKey" small>
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
                    </div>

                    <div v-if="item.educationArray.length">
                        <v-divider class="mx-10"></v-divider>
                        <v-card-title>
                            Education
                        </v-card-title>
                        <v-card-text>
                            <v-timeline align-top dense>
                                <v-timeline-item v-for="(education, edKey) in item.educationArray" :key="edKey" small>
                                    <div>
                                        <div class="font-weight-normal">
                                            from <strong>{{ education.starting_date }}</strong> to <strong>{{ education.completing_date }}</strong>
                                        </div>
                                        <div>Certificate degree: <strong>{{ education.certificate_degree_name }}</strong></div>
                                    </div>
                                </v-timeline-item>
                            </v-timeline>
                        </v-card-text>
                    </div>

                    <div v-if="item.skillArray.length">
                        <v-divider class="mx-10"/>
                        <v-card-title>
                            Skills
                        </v-card-title>
                        <v-card-text>
                            <v-chip-group column>
                                <v-chip v-for="(skill, skillKey) in item.skillArray" :key="skillKey">
                                    {{ skill.name }}
                                </v-chip>
                            </v-chip-group>
                        </v-card-text>
                    </div>

                    <v-card-actions>
                        <v-btn color="primary" @click="updateCV(key)">
                            EDIT
                        </v-btn>
                        <v-btn color="error" @click="deleteCV(item.cv.id)">
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
    name: "ProfileCVCards",
    data() {
        return {
            cvs: [],
        };
    },
    created() {
        this.getCVs();
    },
    methods: {
        updateCV(index) {
            this.$emit('updateCV', this.cvs[index]);
        },
        deleteCV(cv_id) {
            this.$emit('deleteCV', cv_id);
        },
        getCVs() {
            axios.post(
                '/api/get_seeker_cvs',
                {
                    'user_id': this.$store.getters.getCVUserID,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.cvs = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        endDate(icj, date) {
            return (icj) ? 'present' : date;
        },
    },
};
</script>

<style scoped>

</style>
