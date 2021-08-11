<template>
    <div>
        <div class="loader" v-if="loading">
            <v-progress-linear
                color="white"
                indeterminate
            ></v-progress-linear>
        </div>
        <div v-else>
            <div v-if="editAction">
                <section class="d-flex justify-space-around pt-8 flex-wrap">
                    <div class="left d-flex flex-column">
                        <div>
                            <img v-if="company.image === null" :src="'./storage/default.jpg'" alt="">
                            <img v-else :src="'./storage/' + user.id + '_' + company.image" alt="">
                        </div>

                        <div v-if="company.company_website_url">
                            <a class="deep-purple--text" :href="company.company_website_url" v-if="company.company_website_url.length <= 30">
                                {{company.company_website_url}}
                            </a>
                            <a class="deep-purple--text" :href="company.company_website_url" v-else>
                                {{company.company_website_url.substring(0, 30) + '...'}}
                            </a>
                        </div>

                        <div v-if="company.business_stream.length > 0">
                            <p class="font-weight-bold mb-0">Сферы деятельности:</p>
                            <p>
                            <span v-for="(stream, index) in company.business_stream">
                                {{
                                    stream.business_stream_name
                                }}<span v-if="index !== company.business_stream.length - 1">, </span>
                            </span>
                            </p>
                        </div>
                        <div>
                            <v-btn color="primary" @click="$router.push({name: 'edit_company'})">
                                Редактировать
                            </v-btn>
                        </div>

                    </div>


                    <div class="right">
                        <h1 class="text-capitalize mb-4">{{company.company_name}}</h1>
                        <hr class="mb-2">
                        <p>
                            <span class="font-weight-bold">Дата основания: </span>{{company.establishment_date}}
                        </p>
                        <p class="font-weight-bold">Описание</p>
                        <pre id="description">{{company.profile_description}}</pre>

                    </div>
                </section>
            </div>
            <div v-else>
                <v-card width="80%" class="mx-auto my-6 pa-6">
                    <h1>
                        Вы еще не зарегистрировали компанию
                    </h1>
                    <v-btn color="primary" @click="$router.push({name: 'edit_company'})">
                        Создать
                    </v-btn>
                </v-card>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CompanyProfile",
    data() {
        return {
            user: {},
            company: {},
            loading: true,
            editAction: true
        }
    },
    props: [
        "user_info"
    ],

    watch: {
        user_info() {
            this.user = this.user_info
        }
    },

    created() {
        console.log("created")
        this.user = this.user_info
        if(this.$store.getters.getCompanyLoadStatus){
            this.company = this.$store.getters.getCompanyData
            this.editAction = !this.$store.getters.getCreateActionCompany
            this.loading = false
        }
        else{
            this.loadCompanyData()
        }
    },

    methods: {
        loadCompanyData(){
            let user_data = {
                id: this.user.id,
                token : localStorage.getItem("token")
            }

            this.$store.dispatch("loadCompany", user_data)

            this.unwatch = this.$store.watch(
                (state, getters) => getters.getCompanyLoadStatus,
                (newValue, oldValue) => {
                    if(newValue) {
                        this.company = this.$store.getters.getCompanyData
                        this.loading = false

                        if(this.company.id){
                            //update mode
                            this.editAction = true
                        }
                        else{
                            //create mode
                            this.editAction = false
                            this.$store.commit("setCreateActionCompany")
                        }
                    }
                }
            )
        }
    }
}
</script>

<style scoped>

    #description {
        white-space: pre-wrap;
        font-family: Arial,sans-serif;
        font-size:1em;
    }

    hr{
        background: #6200EA;
        height: 2px;
        border: none;
    }

    section {
        margin: auto;
        width: 90%;
    }

    .left{
        background: #f9f9f9;
        width: 210px;
        padding: 20px;
    }
    .left div{
        margin-bottom: 10px;
    }
    .left p {
        font-size: 14px;
    }
    .left a{
        word-wrap: break-word;
    }
    .left img{
        width: 170px;
        height: 170px;
    }

    .right{
        padding-left: 20px;
        width: 70%;
    }

</style>
