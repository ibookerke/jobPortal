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

            <v-menu
                v-model="user_menu"
                :close-on-content-click="true"
                :nudge-width="200"
                offset-y
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon>mdi-account</v-icon>
                    </v-btn>
                </template>

                <v-card v-if="auth">
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>{{user.email}}</v-list-item-title>
                                <v-list-item-subtitle v-if="user.user_type_id === 1">Работдатель</v-list-item-subtitle>
                            </v-list-item-content>

                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-item
                            link
                            @click="$router.push({name: 'profile'})"
                        >
                            <v-list-item-title>Профиль</v-list-item-title>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item
                            link
                            @click="logOut"
                        >
                            <v-list-item-title>Выйти</v-list-item-title>
                        </v-list-item>
                    </v-list>

                </v-card>
                <v-card v-else>
                    <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="text-center">Вы не авторизованы</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list>
                        <v-list-item
                            link
                            @click="$router.push({name: 'login'})"
                        >
                            <v-list-item-title>Войти</v-list-item-title>
                        </v-list-item>

                        <v-list-item
                            link
                            @click="$router.push({name: 'register'})"
                        >
                            <v-list-item-title>Создать аккаунт</v-list-item-title>
                        </v-list-item>

                    </v-list>
                </v-card>
            </v-menu>


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
            auth: false,

            user_menu: false,

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
            this.$store.commit('setJobPostUserID', this.user.id);

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
