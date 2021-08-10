export default {
    state: {
        jpJobTypeArray: [],
        jpSelectedCountry: {},
        jpSelectedState: {},
        jpSelectedCity: {},
    },
    mutations: {
        setJPJobTypeArray(state, data)
        {
            state.jpJobTypeArray = data;
        },
        setJPSelectedCountry(state, data)
        {
            state.jpSelectedCountry = data;
        },
        setJPSelectedState(state, data)
        {
            state.jpSelectedState = data;
        },
        setJPSelectedCity(state, data)
        {
            state.jpSelectedCity = data;
        },

    },
    actions: {
    },
    getters: {
    }
}
