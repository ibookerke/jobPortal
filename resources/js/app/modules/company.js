export default {
    state: {
        company_loading: true,
        company: {
            business_stream: []
        },
        createActionCompany: false,
        image_loading: true,
    },
    mutations: {
        setCompanyData(state, company_data) {
            state.company = company_data
        },
        setCompanyBusinessStreamData(state, business_data) {
            state.company.business_stream = business_data
        },
        startCompanyLoading(state) {
            state.company_loading = true
        },
        stopCompanyLoading(state) {
            state.company_loading = false
        },
        setCreateActionCompany(state) {
            state.createActionCompany = true
        },
        image_upload(state){
            state.image_loading = false
        }
    },
    actions: {
        loadCompany({commit}, data) {
            commit("startCompanyLoading")
            axios.get("/api/getCompany/" + data.id, {
                headers: {
                    "Authorization" : "Bearer " + data.token
                }
            }).then(response => {
                // setTimeout(() => {
                //
                // }, 2000)
                console.log("company loaded: ", response)
                commit("stopCompanyLoading")

                if(response.data.company === null) {
                    commit("setCreateActionCompany")
                }
                else{
                    commit("setCompanyData", response.data.company)
                    commit("setCompanyBusinessStreamData", response.data.business_stream)
                }

            }).catch(err => {
                console.log(err.response)
            })
        }
    },
    getters: {
        getCompanyLoading(state) {
            return state.company_loading
        },
        getCompanyData(state) {
            return state.company
        },
        getCreateActionCompany(state) {
            return state.createActionCompany
        },
        getImageLoading(state){
            return state.image_loading
        }
    }
}
