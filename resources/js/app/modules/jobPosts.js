export default{
    state: {
        job_post_user_id: null,
        job_post_company_id: null,
        job_post_editor_mode: null, // create -> 0, update -> 1, show -> 3
        job_post: null,
    },
    mutations: {
        setJobPostUserID(state, value) {
            state.job_post_user_id = value;
        },
        setJobPostCompanyID(state, company_id) {
            state.job_post_company_id = company_id;
        },
        setJobPostEditorModeCreate(state) {
            state.job_post_editor_mode = 0;
        },
        setJobPostEditorModeUpdate(state) {
            state.job_post_editor_mode = 1;
        },
        setJobPost(state, job_post) {
            state.job_post = job_post;
        }
    },
    getters: {
        getJobPostUserID(state) {
            return state.job_post_user_id;
        },
        getJobPostCompanyID(state) {
            return state.job_post_company_id;
        },
        getJobPostEditorMode(state) {
            return state.job_post_editor_mode;
        },
        getJobPost(state) {
            return state.job_post;
        }
    },
}
