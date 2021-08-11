<template>
    <v-main>
        <section>
            <v-parallax src="./storage/home.jpg" height="600">
                <v-layout column align-center justify-center class="white--text">
                    <v-row align="center" justify="center" class="row_div">
                        <v-text-field
                            v-model="search"
                            v-on:keyup.enter="submit()"
                            :color="color"
                            class="search"
                            :label="label"
                            filled
                            append-icon="mdi-magnify"
                    ></v-text-field>
                    </v-row>
                </v-layout>
            </v-parallax>
        </section>
    </v-main>
</template>

<script>
import user_info from "../app/modules/user_info";

export default {
    name: "HomeComponent",
    props: [
        'user_info'
    ],
    data() {
        return {
            user: {},
            auth: false,
            color: "green accent-4",
            label: "Искать работу",
            search: ""
        }
    },
    created(){
        this.user = this.user_info
        if(this.user.id){
            this.set_setting(this.user.user_type_id)
            this.auth = true
            this.color = "primary"
        }
    },

    methods:{
        submit(){
            this.set_setting(this.user.user_type_id)
            this.$store.commit("setSearch", this.search)
            if(this.user.user_type_id === 1){
                this.$router.push({name: 'cvs'})
            }
            else{
                this.$router.push({name: 'job_posts'})
            }
        },
        set_setting(type){
            if(type === 1){
                this.label = "Искать работников"
            }
        }
    },

    watch:{
        user_info(){
            this.user = this.user_info
            this.set_setting(this.user.user_type_id)
        }
    }
}
</script>

<style scoped>
.row_div{
    width: 60%;
    height: 56px;
    overflow: hidden;
}
.search{
    background: white;
}
</style>
