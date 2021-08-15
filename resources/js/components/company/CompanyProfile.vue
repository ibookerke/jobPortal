<template>
    <div v-if="editAction" class="main_block">
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
                <p v-if="company.establishment_date !== null">
                    <span class="font-weight-bold">Дата основания: </span>{{company.establishment_date}}
                </p>
                <p class="font-weight-bold">Описание</p>
                <pre id="description">{{company.profile_description}}</pre>

            </div>
        </section>
    </div>
    <div v-else class="main_block">
        <v-card width="80%" class="mx-auto my-6 pa-6">
            <h1>
                Вы еще не зарегистрировали компанию
            </h1>
            <v-btn color="primary" @click="$router.push({name: 'edit_company'})">
                Создать
            </v-btn>
        </v-card>
    </div>
</template>

<script>
export default {
    name: "CompanyProfile",
    data() {
        return {
            user: {},
            company: {},
            editAction: true
        }
    },

    computed:{
        companyLoading(){
            return this.$store.getters.getCompanyLoading;
        },
        imageLoading(){
            return this.$store.getters.getImageLoading
        }
    },

    created() {
        if(!this.$store.getters.getCompanyLoading){
            this.user = this.$store.getters.getUserData
            this.company = this.$store.getters.getCompanyData
            this.editAction = !this.$store.getters.getCreateActionCompany
        }
    },

    watch: {
        companyLoading(value){
            if(!value){
                this.company = this.$store.getters.getCompanyData
            }
        },
        imageLoading(value){
            if(!value){
                console.log("image changed")
                this.$nextTick(this.$forceUpdate())
            }
        },
    }

}
</script>

<style scoped>
    .main_block{
        margin-bottom: 140px;
    }

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
