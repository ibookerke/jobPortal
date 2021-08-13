export default {
    state: {
        jpUserID: null,
        jpCompanyID: null,
        jpJobPostID: null, // only for update
        jpEditType: null, // if true -> create, false -> update, null -> redirect
        jpJobPost: {},
        jpJobLocation: {},
        jpJobTypes: [],
        jpSkills: [],
        jpWorkExperience: [],
        jpLocationDetails: null
    },
    mutations: {
        // jpUserID
        setJPUserID(state, data)
        {
            state.jpUserID = data;
        },

        // jpCompanyID
        setJPCompanyID(state, data)
        {
            state.jpCompanyID = data;
        },

        // jpEditType
        setJPEditTypeToCreate(state)
        {
            state.jpEditType = true;
        },
        setJPEditTypeToUpdate(state)
        {
            state.jpEditType = false;
        },

        // jpJobPost
        setJPJobPostForCreate(state)
        {
            state.jpJobPost = {
                user_id: state.jpUserID,
                company_id: state.jpCompanyID,
                location_id: null,
                is_active: 1,
                work_experience_type: null,
                job_description: null,
                job_title: null,
                salary: null
            };
        },
        setJPJobPostForUpdate(state, data)
        {
            state.jpJobPost = data;
        },
        updateJPJobPost(state, data)
        {
            state.jpJobPost = data;
        },

        // jpJobLocation
        setJPJobLocationForCreate(state)
        {
            state.jpJobLocation = {
                address: null,
                city_id: null,
                state_id: null,
                country_id: null
            };
        },
        setJPJobLocationForUpdate(state, data)
        {
            state.jpJobLocation = data;
        },
        updateJPJobLocation(state, data)
        {
            state.jpJobLocation = data;
        },

        // jpJobTypes
        setJPJobTypes(state, data)
        {
            state.jpJobTypes = data;
        },

        // jpSkills
        setJPSkills(state, data)
        {
            state.jpSkills = data;
        },

        // jpJobPostID
        setJPPostID(state, data)
        {
            state.jpJobPostID = data;
        },

        // jpWorkExperience
        setJPWorkExperience(state, data)
        {
            state.jpWorkExperience = data;
        },

        // jpLocationDetails
        setJPLocationDetails(state, data)
        {
            state.jpLocationDetails = data;
        },

        jpClearAll(state)
        {
            state.jpUserID = null;
            state.jpCompanyID = null;
            state.jpJobPostID = null; // only for update
            state.jpEditType = null; // if true -> create, false -> update, null -> redirect
            state.jpJobPost = {};
            state.jpJobLocation = {};
            state.jpJobTypes = [];
            state.jpSkills = [];
            state.jpWorkExperience = [];
            state.jpLocationDetails = null;
        }
    },
    actions: {
    },
    getters: {
        getJPEditType(state) {
            return state.jpEditType;
        },
        getJPJobPost(state) {
            return state.jpJobPost;
        },
        getJPJobPostID(state) {
            return state.jpJobPostID;
        },
        getJPJobTypes(state) {
            return state.jpJobTypes;
        },
        getJPSkills(state) {
            return state.jpSkills;
        },
        getJPJobLocation(state) {
            return state.jpJobLocation;
        },
        getJPUserID(state) {
            return state.jpUserID;
        },
        getJPCompanyID(state) {
            return state.jpCompanyID;
        },
        getJPWorkExperience(state) {
            return state.jpWorkExperience;
        },
        getJPLocationDetails(state) {
            return state.jpLocationDetails;
        }
    }
}
