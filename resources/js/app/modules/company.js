export default {
    state: {
        company_loaded: false,
        company: {}
    },
    mutations: {
        setCompanyData(state, company_data) {
            state.company = company_data
        },
        setCompanyBusinessStreamData(state, business_data) {
            state.company.business_stream = business_data
        },
        setCompanyStatusLoad(state) {
            state.company_loaded = true
        }
    },
    actions: {
        loadCompany({commit}, data) {
            axios.get("/api/getCompany/" + data.id, {
                headers: {
                    "Authorization" : "Bearer " + data.token
                }
            }).then(response => {
                // setTimeout(() => {
                //
                // }, 2000)
                console.log("company loaded: ", response)
                commit("setCompanyStatusLoad")
                commit("setCompanyData", response.data.company)
                commit("setCompanyBusinessStreamData", response.data.business_stream)

            }).catch(err => {
                console.log(err.response)
            })
        }
    },
    getters: {
        getCompanyLoadStatus(state) {
            return state.company_loaded
        },
        getCompanyData(state) {
            return state.company
        }
    }
}
