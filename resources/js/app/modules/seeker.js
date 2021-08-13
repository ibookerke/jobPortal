export default{
    state: {
        user_id: null,
        cv_id: null,
        editor_mode: null, // create -> 0, update -> 1, show -> 3
    },
    mutations: {
        setSeekerUserID(state, user_id) {
            state.user_id = user_id;
        },
        setSeekerCVID(state, cv_id) {
            state.cv_id = cv_id;
        },

        setCVEditorModeCreate(state) {
            state.editor_mode = 0;
        },
        setCVEditorModeUpdate(state) {
            state.editor_mode = 1;
        },

    },
    getters: {
        getSeekerUserID(state) {
            return state.user_id;
        },
        getSeekerCVID(state) {
            return state.cv_id;
        },
        getCVEditorMode(state) {
            return state.editor_mode;
        },

    },
}
