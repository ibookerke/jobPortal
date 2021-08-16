<template>
    <v-container>
        <v-row>
            <v-col>
                <v-card-title>
                    My Responds (Applications)
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="jobPosts"
                    :search="search"
                ></v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Applications",
    data() {
        return {
            user_id: this.$store.getters.getUserData.id,
            search: '',
            headers: [
                {
                    text: 'Job Title',
                    align: 'start',
                    value: 'job_title',
                },
                { text: 'Company Name', value: 'company_name' },
                { text: 'Salary', value: 'salary' },
                { text: 'Required work experience', value: 'work_experience' },
                { text: 'Country', value: 'country' },
                { text: 'State', value: 'state' },
                { text: 'City', value: 'city' },
            ],
            jobPosts: []
        };
    },
    created() {
        this.fetchResondedToJobPosts();
    },
    methods: {
        fetchResondedToJobPosts() {
            axios.post(
                '/api/fetch_responded_to_job_posts',
                {
                    'user_id': this.user_id,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.jobPosts = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        }
    }
}
</script>

<style scoped>

</style>
