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
                    Turan
                </router-link>
            </v-toolbar-title>

            <v-spacer></v-spacer>

<!--            <v-btn icon>-->
<!--                <v-icon>mdi-dots-vertica</v-icon>-->
<!--            </v-btn>-->

            <v-btn icon>
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
                <v-tabs v-if="user.role === 1" align-with-title>
                    <v-tab>
                        <v-tab @click="$router.push('/administrator')">
                            Профиль
                        </v-tab>
                    </v-tab>
                    <v-tab>
                        <router-link :to="{name: 'my_events'}">
                            Мои концерты
                        </router-link>
                    </v-tab>
                </v-tabs>
                <v-tabs v-if="user.role === 2" align-with-title>
                    <v-tab @click="$router.push('/administrator')">
                        Профиль
                    </v-tab>
                    <v-tab @click="$router.push('/admin_locations')">
                        Редактор Локаций
                    </v-tab>
                    <v-tab @click="$router.push('/admin_events')">
                        Редактор Событий
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
        "user"
    ],
    data() {
        return {
            winWidth: window.innerWidth,
            mobile: false,
            tablet: false,
            auth: false
        }
    },

    watch: {
        user() {
            if(this.user) {
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
        logOut() {
            axios.post("/api/auth/logout", {}, {
                headers: {
                    "Authorization" : "Bearer " + localStorage.getItem("token")
                }
            })
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
