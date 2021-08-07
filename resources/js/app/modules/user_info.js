export default {
    state: {
        user: {}
    },
    mutations: {
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
        getUserData(state) {
            return state.user;
        }
    }
}
