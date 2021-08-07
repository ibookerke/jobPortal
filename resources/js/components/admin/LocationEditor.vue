<template>
    <v-card class="mx-auto pa-4 mt-3" width="80%">
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
        >
            <v-text-field
                v-model="name"
                :counter="255"
                :rules="[rules.required]"
                :value="name"
                label="Название"
                required
            ></v-text-field>

            <v-text-field
                v-model="address"
                :value="address"
                :rules="[rules.required]"
                :counter="255"
                label="Адрес"
                hint="Минимум 8 символов"
            ></v-text-field>

            <v-select
                :rules="[rules.required]"
                v-model="city"
                :items="cities"
                :value="city.id"
                item-text="name_ru"
                item-value="id"
                label="Город"
                persistent-hint
                return-object
                single-line
            ></v-select>

            <v-checkbox
                v-model="has_scheme"
                label="Есть схема"
            ></v-checkbox>

            <SchemeBuilder v-if="has_scheme"></SchemeBuilder>
            <v-row v-else class="mb-4">
                <h1>Вот тут логику на случай, если схемы не будет</h1>
            </v-row>

            <v-btn
                class="mb-4"
                color="success"
                @click="saveLocation()"
            >
                Сохранить
            </v-btn>

            <v-btn
                class="mb-4"
                color="warning"
                @click="preview()"
            >
                Предпросмотр
            </v-btn>

        </v-form>

        <v-alert
            class="mt-5"
            :value="alert.show"
            transition="scale-transition"
            dismissible
            :color="alert.error ? 'red' : 'green'"
            elevation="10"
        >
            {{alert.msg}}
        </v-alert>

        <SchemePreview :open="show_preview"></SchemePreview>

    </v-card>
</template>

<script>
import SchemeBuilder from "./SchemeBuilder";
import SchemePreview from "./SchemePreview";

export default {
    name: "LocationEditor",

    props: [
        "cities"
    ],

    components: {
        SchemeBuilder,
        SchemePreview
    },

    data: () => ({
        valid: true,

        alert: {
            show: false,
            error: false,
            msg: "",
        },

        has_scheme: true,
        show_preview: false,

        name: '',
        address: '',
        city: {},
        schema: '',

        rules  : {
            required: value => !!value || 'Обязательно для заполнения.',
        }

    }),

    methods: {
        saveLocation() {
            if(!this.valid){
                this.throwAlert("Форма заполнена с ошибками", "error")
            }
            else if(this.name.trim() === '' || this.address.trim() === '' || Object.keys(this.city).length === 0) {
                console.log("cum")
                this.throwAlert("Не все данные были заполнены", "error")
            }
            else{
                let token = localStorage.getItem("token")

                axios.post("/api/saveLocation", {
                    name: this.name,
                    address: this.address,
                    schema: this.schema,
                    city_id: this.city.id
                }, {
                    headers: {
                        "Authorization" : "Bearer " + token
                    }
                }).then(response => {
                    console.log("Save Location response: ", response)
                    this.throwAlert(response.data.message, response.data.status)
                }).catch(err => {
                    console.log(err.response)
                })
            }
        },

        preview() {
            if(this.show_preview === true){
                this.show_preview = false
                this.$nextTick( () => {
                    this.show_preview = true
                })
            }
            else{
                this.show_preview = true
            }
        },

        throwAlert(msg, status) {
            this.alert.show = true
            this.alert.msg = msg

            if(status === "error") {
                this.alert.error = true
            }

            if(status === "success") {
                this.name = ""
                this.address = ""
                this.city = {}
                this.schema = ""
                this.$refs.form.resetValidation()
            }

            setTimeout(() => {
                this.alert.msg = ""
                this.alert.show = false
                this.alert.error = false
            }, 3000)
        }

    }
}
</script>

<style scoped>

</style>
