export default {
    state: {
        schema: {}
    },
    mutations: {
        setSchemaData(state, data) {
            state.schema = data
        },
        cleanSchema(state) {
            state.schema = {}
        }
    },
    actions: {

    },
    getters: {
        getSchemaData(state) {
            return state.schema;
        }
    }
}
