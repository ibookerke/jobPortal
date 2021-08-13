export default {
    state: {
        cvEditType: null,
        cvID: null,
        cvUserID: null,

        cvExperienceRadio: null,
        cvEducationRadio: null,

        cvRemovedEducationArray: [],
        cvRemovedExperienceArray: [],

        cvProfile: {},

        cvEducation: [],
        cvExperience: [],
        cvSkills: [],

        cvEd: {},
        cvExp: {}
    },
    actions: {

    },
    mutations: {
        setCVUserID(state, id) {
            state.cvUserID = id;
        },
        setCVID(state, id) {
            state.cvID = id;
        },
        setCVTypeCreate(state) {
            state.cvEditType = false;
            state.cvEd = {
                user_id: state.cvUserID,
                cv_id: null,
                certificate_degree_name: null,
                major: null,
                starting_date: null,
                completing_date: null,
                percentage: null,
                cgpa: null,
            };
            state.cvExp = {
                user_id: state.cvUserID,
                cv_id: null,
                is_current_job: true,
                start_date: null,
                end_date: null,
                job_title: null,
                company_name: null,
                job_description: null,
            };
            state.cvProfile = {
                user_id: state.cvUserID,
                job_title: null,
                firstname: null,
                lastname: null,
                date_of_birth: null,
                gender: null,
                phone: null,
                salary: null
            };
        },
        setCVTypeEdit(state) {
            state.cvEditType = true;
            state.cvEd = {
                user_id: state.cvUserID,
                cv_id: state.cvID,
                certificate_degree_name: null,
                major: null,
                starting_date: null,
                completing_date: null,
                percentage: null,
                cgpa: null,
            };
            state.cvExp = {
                user_id: state.cvUserID,
                cv_id: state.cvID,
                is_current_job: true,
                start_date: null,
                end_date: null,
                job_title: null,
                company_name: null,
                job_description: null,
            };
        },
        setCVProfile(state, profile) {
            state.cvProfile = profile;
            state.cvProfile.gender = parseInt(state.cvProfile.gender);
        },
        setCVExperience(state, value) {
            state.cvExperience = value;
        },
        setCVEducation(state, value) {
            state.cvEducation = value;
        },
        setCVSkills(state, value) {
            state.cvSkills = value;
        },

        setCVExperienceRadio(state, val) {
            state.cvExperienceRadio = val;
        },
        setCVEducationRadio(state, val) {
            state.cvEducationRadio = val;
        },

        cvAppendExperience(state, experience) {
            state.cvExperience.push(experience);
        },
        cvRemoveExperience(state, value) {
            state.cvExperience.splice(value, 1);
        },

        cvAppendEducation(state, experience) {
            state.cvEducation.push(experience);
        },
        cvRemoveEducation(state, value) {
            state.cvEducation.splice(value, 1);
        },

        updateRemovedEd(state, value) {
            state.cvRemovedEducationArray.push(value);
        },
        updateRemovedExp(state, value) {
            state.cvRemovedExperienceArray.push(value);
        },

        clearCVEd(state) {
            state.cvEd = {
                user_id: state.cvUserID,
                cv_id: state.cvEditType ? state.cvID : null,
                certificate_degree_name: null,
                major: null,
                starting_date: null,
                completing_date: null,
                percentage: null,
                cgpa: null,
            };
        },
        clearCVExp(state) {
            state.cvExp = {
                user_id: state.cvUserID,
                cv_id: state.cvEditType ? state.cvID : null,
                is_current_job: true,
                start_date: null,
                end_date: null,
                job_title: null,
                company_name: null,
                job_description: null,
            };
        },


        cvClearAll(state) {
            state.cvEditType = null;
            state.cvSeekerID = null;
            state.cvProfile = {};
            state.cvEd = {};
            state.cvExp = {};
            state.cvEducations = [];
            state.cvExperiences = [];
            state.cvSkills = [];
        }
    },
    getters: {
        getCVEditType(state) {
            return state.cvEditType;
        },
        getCVUserID(state) {
            return state.cvUserID;
        },
        getCVProfile(state) {
            return state.cvProfile;
        },
        getCVEducation(state) {
            return state.cvEducation;
        },
        getCVExperience(state) {
            return state.cvExperience;
        },
        getCVSkills(state) {
            return state.cvSkills;
        },
        getCVExp(state) {
            return state.cvExp;
        },
        getCVEd(state) {
            return state.cvEd;
        },
        getCVExperienceRadio(state) {
            return state.cvExperienceRadio;
        },
        getCVEducationRadio(state) {
            return state.cvEducationRadio;
        },
        getCVRemovedEducationArray(state) {
            return state.cvRemovedEducationArray;
        },
        getCVRemovedExperienceArray(state) {
            return state.cvRemovedExperienceArray;
        },

    }
}
