export default{
    state: {
        grid: [],
        row: {},
        gridLoadStatus: false
    },
    mutations: {
        setGrid(state, data){
            state.grid = data
        },
        setRow(state, data){
            state.row = data;
        },
        setGridLoadStatus(state){
            state.gridLoadStatus = true
        }
    },
    getters: {
        getGrid(state){
            return state.grid
        },
        getRow(state){
            return state.row
        },
        getGridLoadStatus(state) {
            return state.gridLoadStatus
        }
    },
    actions: {
        loadGrid({commit}){
            axios.post("/api/getCVs", {}, {
                headers:{
                    "Authorization" : "Bearer " + localStorage.getItem("token")
                }
            }).then(response=> {
                console.log("cvs: ", response)
                commit("setGrid", response.data)
                commit("setGridLoadStatus")

            }).catch(err=>{
                console.log(err.response)
            })
        }
    }
}
