export default {
    state: {
        user: {},
        user_loading: true
    },
    mutations: {
        startUserLoading(state) {
            state.user_loading = true
        },
        stopUserLoading(state) {
            state.user_loading = false
        },
        setUserInfo(state, user_data) {
            state.user = user_data
        },
        userLogOut(state) {
            state.user = {}
        }
    },
    actions: {

    },
    getters: {
        getUserLoadingStatus(state) {
            return state.user_loading
        },
        getUserData(state) {
            return state.user;
        }
    }
}
