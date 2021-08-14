export default{
    state: {
        seeker_user_id: null,
        seeker_cv_id: null,
        seeker_editor_mode: null, // create -> 0, update -> 1, show -> 3
        seeker_cv: null,
    },
    mutations: {
        setSeekerUserID(state, user_id) {
            state.seeker_user_id = user_id;
        },
        setSeekerCVID(state, cv_id) {
            state.seeker_cv_id = cv_id;
        },

        setCVEditorModeCreate(state) {
            state.seeker_editor_mode = 0;
        },
        setCVEditorModeUpdate(state) {
            state.seeker_editor_mode = 1;
        },

        setSeekerCV(state, cv) {
            state.seeker_cv = cv;
        }
    },
    getters: {
        getSeekerUserID(state) {
            return state.seeker_user_id;
        },
        getSeekerCVID(state) {
            return state.seeker_cv_id;
        },
        getCVEditorMode(state) {
            return state.seeker_editor_mode;
        },
        getSeekerCV(state) {
            return state.seeker_cv;
        }
    },
}
