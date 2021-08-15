<template>
    <div>
        <v-container style="margin-bottom: 140px !important">
            <v-row>
                <v-col class="text-center">
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-col>
            </v-row>
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
                                ></v-radio>
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
                            <small>Showing 1-12 of 200 products</small>
                        </v-col>
<!--                        <v-col cols="12" sm="4">-->
<!--                            <v-select class="pa-0" v-model="select" :items="options" style="margin-bottom: -20px;" outlined dense></v-select>-->
<!--                        </v-col>-->
                    </v-row>

                    <v-divider/>

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
                                        >
                                            RESPOND
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>

                    <div class="text-center mt-12">
                        <v-pagination
                            v-model="page"
                            :length="6"
                        ></v-pagination>
                    </div>
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
            search: null,
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

            page: null,
            jobPosts: [],
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
        }
    },
    methods: {
        fetchJobPosts() {
            axios.post(
                '/api/fetch_job_posts',
                {
                    'selected': this.selected,
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                console.log(response.data)
                this.jobPosts = response.data;
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
        }
    }
}
</script>

<style scoped>

</style>
