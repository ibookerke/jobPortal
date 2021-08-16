<template>
    <div>
        <v-container style="margin-bottom: 140px !important">
            <div class="row">
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <v-text-field
                        v-model="search"
                        placeholder="Search by job titles"
                    ></v-text-field>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12 mt-3">
                    <v-btn
                        block
                        color="primary"
                        @click="fetchJobPosts"
                    >
                        SEARCH
                    </v-btn>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <v-card outlined>
                        <v-card-title class="pb-0">{{labels.work_experience}}</v-card-title>
                        <v-container class="pt-0"  fluid>
                            <v-radio-group class="pt-0" v-model="selected.work_experience">
                                <v-radio v-for="(item, key) in workExperience"
                                         :label="item.name"
                                         :value="item.id"
                                         :key="key"
                                         @click="uncheck(item.id)"
                                />
                            </v-radio-group>
                        </v-container>

                        <v-divider/>
                        <v-card-title class="pb-0">{{labels.job_type}}</v-card-title>
                        <v-container class="pt-0"  fluid>
                            <v-checkbox @click="selectJobType(item.id)" v-for="(item, key) in jobType" :label="item.name" :key="key" hide-details dense></v-checkbox>
                        </v-container>

                        <v-divider/>
                        <v-card-title class="pb-0">Salary</v-card-title>
                        <v-container class="pt-0"  fluid>
                            <v-radio-group class="pt-0" v-model="selected.salary">
                                <v-radio v-for="(item, key) in salaryRadio"
                                         :label="item"
                                         :value="key"
                                         :key="key"
                                />
                            </v-radio-group>
                        </v-container>

                        <v-divider/>
                        <v-card-title>Location</v-card-title>
                        <v-container style="max-height: 200px" class="overflow-y-auto" fluid>
                            <v-checkbox @click="selectLocation(item.id)" v-for="(item, key) in cities" :label="item.name" :key="key" hide-details dense/>
                        </v-container>

                        <v-divider class="mt-7"/>
                        <v-card-title>Business Stream</v-card-title>
                        <v-container style="max-height: 200px" class="overflow-y-auto pt-0" fluid>
                            <v-checkbox @click="selectBusinessStream(item.id)" v-for="(item, key) in businessStream" :label="item.business_stream_name" :key="key" hide-details dense/>
                        </v-container>
                    </v-card>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <v-row dense>
                        <v-col cols="12" sm="8" class="pl-6 pt-6">
                            <small>Showing {{ (page - 1) * 5 + 1 }}-{{ ((page * 5) >= countPosts) ? countPosts : page }} of {{countPosts}} products</small>
                        </v-col>
<!--                        <v-col cols="12" sm="4">-->
<!--                            <v-select class="pa-0" v-model="select" :items="options" style="margin-bottom: -20px;" outlined dense></v-select>-->
<!--                        </v-col>-->
                    </v-row>

                    <v-divider/>

                    <div v-if="jobPosts.length">
                        <v-container>
                            <v-row  v-for="(item, key) in jobPosts" :key="key">
                                <v-col>
                                    <v-card
                                        elevation="2"
                                        outlined
                                        class="mx-auto"
                                    >
                                        <v-card-title>{{item.job_title}}</v-card-title>
                                        <v-card-actions>
                                            <v-btn
                                                color="green"
                                                @click="respondToJobPostConfirmation(item.id)"
                                            >
                                                RESPOND
                                            </v-btn>
                                            <v-dialog v-model="dialog"
                                                max-width="500px"
                                            >
                                                <v-card>
                                                    <v-card-title>
                                                        Choose CV to respond
                                                    </v-card-title>
                                                    <v-card-text>
                                                        <v-select
                                                            v-model="selectedCV"
                                                            :items="cvs"
                                                            item-text="job_title"
                                                            item-value="id"
                                                            placeholder="Select CV"
                                                        ></v-select>
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-btn
                                                            color="primary"
                                                            @click="respondToJobPost"
                                                        >
                                                            RESPOND
                                                        </v-btn>
                                                        <v-btn
                                                            color="error"
                                                            @click="dialog = false"
                                                        >
                                                            CLOSE
                                                        </v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </v-card-actions>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-container>

                        <div class="text-center mt-12">
                            <v-pagination
                                v-model="page"
                                :length="maxPage"
                                prev-icon="mdi-menu-left"
                                next-icon="mdi-menu-right"
                            ></v-pagination>
                        </div>
                    </div>
                    <div v-else class="text-center"><h3>No job posts found</h3></div>
                </div>
            </div>
        </v-container>
    </div>
</template>

<script>
export default {
    name: "JobPosts",
    data() {
        return {
            search: '',
            workExperience: [],
            jobType: [],
            salaryRadio: [
                "Doesn't matter",
                'from 141 900 KZT',
                'from 283 600 KZT',
                'from 425 300 KZT',
                'from 567 000 KZT',
                'from 708 700 KZT'
            ],
            cities: [],
            businessStream: [],
            selected: {
                work_experience: null,
                job_type: [],
                salary: null,
                cities: [],
                business_stream: [],
            },
            labels: {
                work_experience: 'Work Experience',
                job_type: 'Employment Type',
            },
            previouslySelectedWE: null,
            page: 1,
            maxPage: 1,
            countPosts: 0,
            jobPosts: [],

            // respond logic
            user_id: this.$store.getters.getUserData.id,
            cvs: [],
            dialog: false,
            selectedCV: null,
            selectedJobPost: null,
        };
    },
    created() {
        this.getFiltersBase();
        this.fetchJobPosts();
    },
    watch: {
        selected: {
            handler: function (value) {
                this.fetchJobPosts();
            },
            deep: true
        },
        search: function (val) {
            if(val.length === 0) {
                this.fetchJobPosts();
            }
        },
        page: function (newVal) {
            this.fetchJobPosts(newVal);
        }
    },
    methods: {
        respondToJobPost() {
            axios.post(
                '/api/seeker_respond_to_job_post',
                {
                    'cv_id': this.selectedCV,
                    'job_post_id': this.selectedJobPost,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                if (response.status === 200) {
                    this.dialog = false;
                    this.fetchJobPosts();
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        respondToJobPostConfirmation(job_post_id) {
            this.selectedJobPost = job_post_id;
            axios.post(
                '/api/get_seeker_cvs_for_respond',
                {
                    'user_id': this.user_id,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.cvs = response.data;
                if (this.cvs.length > 1) {
                    this.dialog = true;
                }
                else if (this.cvs.length === 1) {
                    this.selectedCV = this.cvs[0].id;
                    this.respondToJobPost();
                }
                else {
                    alert('you have no cvs to respond!');
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        fetchJobPosts(page=0) {
            axios.post(
                '/api/fetch_job_posts',
                {
                    'selected': this.selected,
                    'search': this.search,
                    'page': page,
                    'user_id': this.user_id,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.jobPosts = response.data.data;
                if (page === 0) {
                    this.countPosts = response.data.count_posts;
                    this.maxPage = Math.ceil(this.countPosts / 5);
                }
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        getFiltersBase() {
            axios.post(
                '/api/get_main_page_filters',
                {
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                let data = response.data;
                this.workExperience = data.work_experience;
                this.jobType = data.job_type;
                this.cities = data.cities;
                this.businessStream = data.business_stream;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        selectJobType(id) {
            let index = this.selected.job_type.indexOf(id);
            if (index === -1) {this.selected.job_type.push(id);}
            else {this.selected.job_type.splice(index, 1);}
        },
        selectLocation(id) {
            let index = this.selected.cities.indexOf(id);
            if (index === -1) {this.selected.cities.push(id);}
            else {this.selected.cities.splice(index, 1);}
        },
        selectBusinessStream(id) {
            let index = this.selected.business_stream.indexOf(id);
            if (index === -1) {this.selected.business_stream.push(id);}
            else {this.selected.business_stream.splice(index, 1);}
        },
        uncheck: function(val) {
            if (val === this.previouslySelectedWE) {
                this.selected.work_experience = false;
            }
            this.previouslySelectedWE = this.selected.work_experience;
        },
    }
}
</script>

<style scoped>

</style>
