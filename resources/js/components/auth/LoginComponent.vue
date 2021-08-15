<template>
    <v-card class="mx-auto pa-4 mt-7" min-width="300px" max-width="600px" >
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
        >
            <v-text-field
                v-model="email"
                :counter="255"
                :rules="[rules.required, rules.email]"
                label="email"
                required
            ></v-text-field>

            <v-text-field
                v-model="password"
                :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required, rules.password]"
                :type="show ? 'text' : 'password'"
                name="input-10-2"
                label="Пароль"
                hint="Минимум 8 символов"
                class="input-group--focused"
                @click:append="show = !show"
                @keypress="inputEnter($event)"
            ></v-text-field>

            <div id="form_footer">
                <v-btn
                    color="success"
                    @click="login()"
                >
                    Войти
                </v-btn>

                <p @click="$router.push({name: 'register'})">
                    Зарегистрироваться
                </p>
            </div>
        </v-form>
        <v-alert
            class="mt-5"
            :value="has_error"
            transition="scale-transition"
            dismissible
            color="red"
            elevation="10"
            type="error"
        >
            {{error}}
        </v-alert>
    </v-card>
</template>

<script>

import Register from "./Register";

export default {
    name: "LoginComponent",
    components: {
        Register
    },
    data: () => ({
        show: false, //show password button
        valid: true,
        error: "",
        has_error: false,

        email: '',
        password: '',

        rules  : {
            required: value => !!value || 'Обязательно для заполнения.',
            password: v => (v && v.length >= 8) || 'Пароль не может быть короче 8 символов',
            email: v => !v || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Некорректный email'
        }

    }),
    methods: {
        inputEnter: function($event){
            if($event.charCode === 13){
                this.login()
            }
        },

        login() {
            axios.post('/api/auth/login', {
                "email": this.email,
                "password" : this.password
            }).then(response => {
                console.log(response)
                localStorage.setItem("token", response.data.data.original.access_token)
                this.$store.commit('setUserInfo', response.data.data.original.user)
                this.$store.commit("stopUserLoading")
                this.$store.commit("authenticate")
                this.$router.go() //reloads the page

            }).catch(error=> {

                if(error.response){
                    this.error = error.response.data.message
                }
                else{
                    this.error = "Произошла оишбка"
                }
                this.has_error = true
                setTimeout(() => {
                    this.error = ""
                    this.has_error = false
                }, 3000)
            })

        }
    },
}
</script>

<style scoped>
    #form_footer{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #form_footer p{
        margin: 0;
        color: #2998ff;
        text-decoration: underline;
        cursor: pointer;
    }
    #form_footer p:hover{
        color: #43a047;
    }
</style>
