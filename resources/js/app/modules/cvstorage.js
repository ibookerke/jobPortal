export default {
    state: {
        cvProfile: {
            user_id: null,
            job_title: null,
            firstname: null,
            lastname: null,
            date_of_birth: null,
            gender: null,
            phone: null,
            salary: null,
            currency: null
        }
    },
    actions: {

    },
    mutations: {
        cvUpdateProfile(state, data) {
            state.cvProfile = data
        }
    },
    getters: {
        getCvProfile(state) {
            return state.cvProfile
        }
    }
}
