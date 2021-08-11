<template>
    <v-card class="mx-auto pa-4 mt-3" min-width="300px" max-width="600px" >
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
                type="text"
                autocomplete="off"
                required
            ></v-text-field>

            <v-radio-group
                v-model="user_type_id"
                row
                required
            >
                <v-radio
                    label="Ищу работу"
                    color="success"
                    :value="2"
                ></v-radio>
                <v-radio
                    label="Ищу работников"
                    color="success"
                    :value="1"
                ></v-radio>
            </v-radio-group>

            <v-text-field
                v-model="password"
                :rules="[rules.required, rules.password]"
                :type="fieldTypes.password"
                label="Пароль"
                hint="Минимум 8 символов"
                autocomplete="off"
                name="password"
                @focus="handleType"
                @blur="handleType"
            ></v-text-field>
            <v-text-field
                v-model="password_confirmation"
                :rules="[rules.required, rules.password]"
                :type="fieldTypes.password"
                label="Потвердите пароль"
                hint="Минимум 8 символов"
                autocomplete="off"
                name="password"
                @focus="handleType"
                @blur="handleType"
            ></v-text-field>

            <v-btn
                color="success"
                @click="register()"
            >
                Зарегистрироваться
            </v-btn>
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
export default {
    name: "Register",
    data: () => ({
        valid: true,
        error: "",
        has_error: false,

        email: '',
        password: '',
        user_type_id: null,
        password_confirmation: '',

        fieldTypes: {
            password: 'text',
        },

        rules : {
            required: value => !!value || 'Обязательно для заполнения.',
            password: v => (v && v.length >= 8) || 'Пароль не может быть короче 8 символов',
            email: v => !v || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Некорректный email'
        }

    }),

    methods: {

        handleType(event) {
            const { srcElement, type } = event;
            const { name, value } = srcElement;
            this.fieldTypes[name] = 'password'
            if(type === 'blur' && !value) {
                this.fieldTypes[name] = 'password'
            } else {
                this.fieldTypes[name] = 'password'
            }
        },

        register() {
            axios.post('/api/auth/register', {
                "email": this.email,
                "password" : this.password,
                "password_confirmation" : this.password_confirmation,
                "user_type_id" : this.user_type_id

            }).then(response => {
                console.log(response)
                if(response.status === 201) {
                    alert("Пользователь успешно создан")
                    this.$router.push({name: "login"})
                }
            }).catch(error=> {
                console.log(error.response)
                if(error.response){
                    this.error = "Форма заполнена с ошибками"
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

</style>
