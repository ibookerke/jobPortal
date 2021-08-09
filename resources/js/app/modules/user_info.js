import router from "../router";

export default {
    state: {
        user: {},
        user_loading: true,
        authenticated: false,
    },
    mutations: {
        startUserLoading(state) {
            state.user_loading = true
        },

        stopUserLoading(state) {
            state.user_loading = false
        },

        authenticate(state) {
            state.authenticated = true
        },

        setUserInfo(state, user_data) {
            state.user = user_data
        },

        userLogOut(state) {
            state.user = {}
            state.authenticated = false
            localStorage.clear()
            router.push("/login")
            router.go()
        }
    },
    actions: {
        LoadUser({commit}, token) {
            commit("startUserLoading")
            axios.get("/api/user-profile", {
                headers: {
                    "Authorization" : "Bearer " + token
                }
            }).then(response => {
                console.log("data fetched", response.data)
                commit("setUserInfo", response.data)
                commit("stopUserLoading")
                commit('authenticate')

            }).catch(error=> {
                console.log(error.response)
                commit("stopUserLoading")
                commit("userLogOut")
            })
        },
        Logout({commit}, token) {
            axios.post("/api/logout", {}, {
                headers: {
                    "Authorization" : "Bearer " + token
                }
            })

            commit("userLogOut")
        }
    },
    getters: {
        getUserLoadingStatus(state) {
            return state.user_loading
        },
        getUserData(state) {
            return state.user;
        },
        getAuthStatus(state) {
            return state.authenticated
        }
    }
}
