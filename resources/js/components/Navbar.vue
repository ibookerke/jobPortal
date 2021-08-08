<template>
    <div>
        <v-app-bar
            :color="auth ? 'deep-purple accent-4' : 'green accent-4'"
            dense
            dark
        >
            <v-app-bar-nav-icon v-if="mobile || tablet"></v-app-bar-nav-icon>

            <v-toolbar-title>
                <router-link to="/" class="title">
                    JobPortal
                </router-link>
            </v-toolbar-title>

            <v-spacer></v-spacer>

<!--            <v-btn icon>-->
<!--                <v-icon>mdi-dots-vertica</v-icon>-->
<!--            </v-btn>-->

            <v-btn icon @click="test">
                <v-icon>mdi-magnify</v-icon>
            </v-btn>

            <v-menu
                left
                bottom
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon>mdi-cog</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item
                        v-for="n in 5"
                        :key="n"
                        @click="() => {}"
                    >
                        <v-list-item-title>Option {{ n }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>

            <v-btn icon v-if="auth" @click="logOut">
                <v-icon>mdi-logout</v-icon>
            </v-btn>


            <template v-if="auth" v-slot:extension>
                <v-tabs v-if="user.user_type_id === 1" align-with-title>
                    <v-tab @click="$router.push('/profile')">
                        Профиль
                    </v-tab>
                    <v-tab @click="$router.push('/my_job_posts')">
                        Мои вакансии
                    </v-tab>
                </v-tabs>
                <v-tabs v-if="user.user_type_id === 2" align-with-title>
                    <v-tab @click="$router.push('/profile')">
                        Профиль
                    </v-tab>
                    <v-tab @click="$router.push('/applications')">
                        Отклики
                    </v-tab>
                </v-tabs>
            </template>
        </v-app-bar>

    </div>
</template>

<script>
export default {
    name: "Navbar",
    props: [
        "user_info"
    ],
    data() {
        return {
            user: {},

            winWidth: window.innerWidth,
            mobile: false,
            tablet: false,
            auth: false
        }
    },

    created(){
        this.user = this.user_info
        if(this.user.email) {
            this.auth = true
        }
    },

    watch: {
        user_info() {
            this.user = this.user_info

            if(this.user.email) {
                this.auth = true
            }
        },

        winWidth() {
            this.mobile = this.winWidth < 400
            if(this.mobile) {
                console.log("switched to mobile mode")
            }

            if(this.mobile === false){
                this.tablet = this.winWidth < 700
                if(this.tablet){
                    console.log("switched to tablet mode")

                }
            }

        }
    },

    mounted() {
        this.$nextTick( () => {
            window.addEventListener('resize', this.onResize)
        })
    },

    beforeDestroy() {
        window.removeEventListener('resize', this.onResize);
    },

    methods: {
        test() {
            console.log(this.$store.getters.getCvProfile)
        },

        logOut() {
            this.$store.commit('userLogOut')
            localStorage.clear()
            this.$router.go() //reloads the page
        },

        onResize() {
            this.winWidth = window.innerWidth
        }
    }
}
</script>

<style scoped>
.title {
    color: white;
    text-decoration: none;
}
</style>
