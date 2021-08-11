<template>
    <v-container style="margin-bottom: 140px !important">
        <v-row>
            <v-col>
                <v-btn
                    @click="createCV"
                    rounded
                    color="primary"
                    dark
                >
                    Create CV
                </v-btn>
            </v-col>
        </v-row>
        <v-row v-if="cvs.length" v-for="(item, key) in cvs" :key="key">
            <v-col>
                <v-card class="mx-auto" elevation="10" outlined shaped>
                    <v-card-title>
                        <h2>
                            {{ item.cv.job_title }}
                        </h2>
                    </v-card-title>
                    <v-card-text>
                        <div class="text-subtitle-1">
                            $ • {{ item.cv.salary }}
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

                    <v-card-actions>
                        <v-btn color="primary" @click="editCV(key)">
                            EDIT
                        </v-btn>
                        <v-btn color="error" @click="deleteCV(item.cv.id)">
                            DELETE
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "SeekerProfile",
    data() {
        return {
            user: {},
            cvs: [],
        }
    },
    props: [
        "user_info"
    ],
    methods: {
        getCVs() {
            axios.post(
                '/api/get_seeker_cvs',
                {
                    'user_id': this.user.id,
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
        createCV(cv_id) {
            this.$store.commit('setCVTypeCreate');
            this.$router.push({name: 'save_cv'});
        },
        editCV(index) {
            this.$store.commit('setCVTypeEdit');
            this.$store.commit('setCVID', this.cvs[index].cv['id']);
            this.$store.commit('setCVProfile', this.cvs[index].cv);
            this.$store.commit('setCVEducation', this.cvs[index].educations);
            this.$store.commit('setCVExperience', this.cvs[index].experiences);
            this.$store.commit('setCVSkills', this.cvs[index].skills);
            this.$router.push({name: 'save_cv'});
        },
        deleteCV(cv_id) {
            axios.post(
                '/api/delete_seeker_cv',
                {
                    'cv_id': cv_id,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                if (response.status === 200)
                {
                    this.getCVs();
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        endDate(icj, date) {
            return (icj) ? 'present' : date;
        }
    },
    watch: {
        user_info() {
            this.user = this.user_info
        }
    },
    created() {
        this.user = this.user_info;
        this.$store.commit('setCVUserID', this.user.id);
        this.getCVs();
    }
}
</script>

<style scoped>

</style>
