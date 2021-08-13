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
        <profile-c-v-cards :key="forceReRenderCVCards" @updateCV="updateCV" @deleteCV="deleteCV"/>
    </v-container>
</template>

<script>
import ProfileCVCards from "./ProfileCVCards";
export default {
    name: "SeekerProfile",
    components: {ProfileCVCards},
    data() {
        return {
            user: {},
            forceReRenderCVCards: 0,
        }
    },
    props: [
        "user_info"
    ],
    created() {
        this.user = this.user_info;
        this.$store.commit('setSeekerUserID', this.user.id);
        this.forceReRenderCVCards += 1; // for re-rendering of ProfileCVCards
    },
    watch: {
        user_info() {
            this.user = this.user_info;
        }
    },
    methods: {
        createCV() {
            this.$store.commit('setCVEditorModeCreate');
            this.$router.push({name: 'cv_editor'});
        },
        updateCV(cv) { // pushes all data to storage, which will be fetched there
            this.$store.commit('setCVEditorModeUpdate');
            this.$store.commit('setCVID', cv.cv['id']);
            this.$store.commit('setCVProfile', cv.cv);
            this.$store.commit('setCVEducation', cv.educationArray);
            this.$store.commit('setCVExperience', cv.experienceArray);
            this.$store.commit('setCVSkills', cv.skillArray);
            this.$router.push({name: 'cv_editor'});
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
                    // forces ProfileCVCards to re-render, so it fetches all user cvs again
                    this.forceReRenderCVCards += 1;
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
    },
}
</script>

<style scoped>

</style>
