<template>
    <div>
        <h1 v-if="loading">Компания грузится</h1>
        <div v-else>
            <h1 v-if="this.company === null">Компания отсутствует у пользователя</h1>
            <div v-else-if="this.company.id">
                <section class="d-flex justify-space-between pt-8">
                    <div class="left d-flex flex-column">
                        <div>
                            <img :src="'./storage/default.jpg'" alt="">
                        </div>

                        <div>
                            <a class="deep-purple--text" :href="company.company_website_url">
                                {{company.company_website_url}}
                            </a>
                        </div>

                        <div>
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
                            <v-btn color="primary">
                                Редактировать
                            </v-btn>
                        </div>

                    </div>




                    <div class="right">
                        <h1 class="text-capitalize">{{company.company_name}}</h1>
                        <p class="font-weight-bold">Описание</p>
                        <p>
                            {{company.profile_description}}
                        </p>

                    </div>
                </section>
            </div>
        </div>

        <h1>Hello, company  {{user.email}}</h1>
    </div>
</template>

<script>
export default {
    name: "CompanyProfile",
    data() {
        return {
            user: {},
            company: {},
            loading: true
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
        this.user = this.user_info
        if(this.$store.getters.getCompanyLoadStatus){
            this.company = this.$store.getters.getCompanyData
            this.loading = false
        }
        else{
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
                    }
                }
            )
        }
    },
}
</script>

<style scoped>

    section {
        margin: auto;
        width: 90%;
    }

    section div{

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
    .left img{
        width: 170px;
        height: 170px;
    }
    .right{
        padding-left: 20px;
        width: 70%;
    }

</style>
