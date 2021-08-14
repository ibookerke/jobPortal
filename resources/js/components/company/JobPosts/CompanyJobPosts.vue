<template>
    <v-container style="margin-bottom: 140px">
        <h1>ВОт тут будет грид(я сам сделаю)</h1>
        <v-row>
            <v-col>
                <v-btn @click="createJobPost" color="success">
                    Create new job post
                </v-btn>
            </v-col>
        </v-row>
        <job-post-cards :companyID="company_id"
                        :userID="user_id"
                        :key="forceReRenderJobPostCards"
                        @updateJobPost="updateJobPost"
                        @deleteJobPost="deleteJobPost"/>
    </v-container>
</template>

<script>
import JobPostCards from "./JobPostCards";
export default {
    name: "CompanyJobPosts",
    components: {JobPostCards},
    data() {
        return {
            user_id: null,
            company_id: null,
            forceReRenderJobPostCards: 0,
        };
    },
    computed: {
        userID() {
            return this.$store.getters.getJobPostUserID;
        }
    },
    watch: {
        userID(value) {
            let store = this.$store;
            let get = store.getters;
            this.user_id = value;
            if(this.$store.getters.getCompanyLoadStatus)
            {
                this.company_id = get.getCompanyData.id;
                store.commit('setJobPostCompanyID', this.company_id);
                this.forceReRenderJobPostCards += 1;
            }
            else
            {
                this.loadCompanyData();
            }
        }
    },
    created() {
        if(this.$store.getters.getCompanyLoadStatus)
        {
            let store = this.$store;
            let get = store.getters;
            this.user_id = this.$store.getters.getJobPostUserID;
            this.company_id = get.getCompanyData.id;
            store.commit('setJobPostCompanyID', this.company_id);
            this.forceReRenderJobPostCards += 1;
        }
    },
    methods: {
        createJobPost() {
            this.$store.commit('setJobPostEditorModeCreate');
            this.$router.push({name: 'edit_job_post'});
        },
        updateJobPost(value) {
            this.$store.commit('setJobPostEditorModeUpdate');
            this.$store.commit('setJobPost', value);
            this.$router.push({name: 'edit_job_post'});
        },
        deleteJobPost(value) {
            axios.post(
                '/api/delete_job_post',
                {
                    'job_post_id': value,
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
                    // forces JobPostCards to re-render, so it fetches all company Vacancies again
                    this.forceReRenderJobPostCards += 1;
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        loadCompanyData(){
            let user_data = {
                id: this.user_id,
                token : localStorage.getItem("token"),
            };
            this.$store.dispatch("loadCompany", user_data);
            this.unwatch = this.$store.watch(
                (state, getters) => getters.getCompanyLoadStatus,
                (newValue, oldValue) => {
                    if(newValue) {
                        this.company_id = this.$store.getters.getCompanyData.id;
                        this.$store.commit('setJobPostCompanyID', this.company_id);
                        this.forceReRenderJobPostCards += 1;
                    }
                },
            );
        }
    }
}
</script>

<style scoped>

</style>
