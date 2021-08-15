<template>
    <v-card class="mx-auto pa-4 mt-6 card_body" width="80%">
        <v-form
            ref="company_form"
            lazy-validation
        >
            <v-row>
                <v-col class="col-9">
                    <v-text-field
                        label="Название"
                        outlined
                        v-model="company.company_name"

                        :error-messages="companyNameErrors"
                        :counter="255"
                        @input="$v.company.company_name.$touch()"
                        @blur="$v.company.company_name.$touch()"
                    ></v-text-field>
                    <v-menu
                        v-model="menu"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="company.establishment_date"
                                prepend-icon="mdi-calendar"
                                label="Дата основания"
                                readonly
                                v-bind="attrs"
                                v-on="on"

                                :error-messages="establishmentDateErrors"
                                @input="$v.company.establishment_date.$touch()"
                                @blur="$v.company.establishment_date.$touch()"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="company.establishment_date"
                            @input="menu = false"
                            :max="new Date().toISOString().slice(0,10)"
                            elevation="15"
                        ></v-date-picker>
                    </v-menu>

                </v-col>
                <v-col class="col-3 d-flex flex-column right align-center">
                    <v-hover v-slot="{ hover }">
                        <v-card
                            :elevation="hover ? 12 : 2"
                            :class="{ 'on-hover': hover } + ' profile_image'"
                        >

                            <v-img
                                :src="!items[0].image ? profile_photo : items[0].image"
                                id="ava"
                                height="170px"
                                width="170px"
                            >
                                <v-card-title class="text-h6 white--text hover_height" >
                                    <v-row
                                        class="flex-column justify-end hover_height"
                                    >
                                        <div class="align-self-center">
                                            <input type="file" id="photo" @change="onFileChange(items[0], $event)">
                                            <label for="photo">
                                                <v-btn
                                                    :class="{ 'show-btns': hover }"
                                                    :color="transparent"
                                                    icon
                                                >
                                                    <v-icon
                                                        :class="{ 'show-btns': hover }"
                                                        :color="transparent"
                                                        @click="previewFiles"
                                                    >
                                                        mdi-content-save-edit
                                                    </v-icon>
                                                </v-btn>
                                            </label>
                                        </div>
                                    </v-row>
                                </v-card-title>
                            </v-img>
                        </v-card>
                    </v-hover>

                </v-col>
            </v-row>

            <v-row>
                <v-col class="col-12">
                    <v-text-field
                        label="Ссылка на сайт компании"
                        outlined
                        :counter="500"
                        v-model="company.company_website_url"

                        :error-messages="companyWebsiteURLErrors"
                        @input="$v.company.company_website_url.$touch()"
                        @blur="$v.company.company_website_url.$touch()"
                    ></v-text-field>
                </v-col>

            </v-row>
            <v-row>
                <v-col>
                    <v-textarea
                        outlined
                        name="input-7-4"
                        label="Описание"
                        rows="3"
                        auto-grow
                        v-model="company.profile_description"

                        :error-messages="profileDescriptionErrors"
                        @input="$v.company.profile_description.$touch()"
                        @blur="$v.company.profile_description.$touch()"
                    ></v-textarea>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-container>
                        <v-row>
                            <v-col>
                                <h2>Сферы деятельности</h2>
                            </v-col>
                        </v-row>
                        <v-combobox
                            v-model="company.business_stream"
                            :items="business_items"
                            item-text="business_stream_name"
                            item-value="id"
                            :search-input.sync="search"
                            hide-selected
                            hint="до 10"
                            multiple
                            persistent-hint
                            small-chips
                        >
                        </v-combobox>
                    </v-container>
                </v-col>
            </v-row>


            <v-row>
                <v-col>
                    <v-btn
                        v-if="!actionCreate"
                        class="mb-4"
                        color="success"
                        @click="updateCompany"
                    >
                        Сохранить
                    </v-btn>
                    <v-btn
                        v-else
                        class="mb-4"
                        color="success"
                        @click="createCompany"
                    >
                        Сохранить
                    </v-btn>
                </v-col>
            </v-row>

        </v-form>

    </v-card>
</template>

<script>
import { validationMixin } from 'vuelidate';
import {required, minLength, maxLength, integer, minValue, maxValue, url} from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],
    name: "CompanyEditor",
    data() {
        return {
            user: {},
            //company object
            company: {
                business_stream: []
            },
            //for showing the date picker card
            menu: false,
            transparent: 'rgba(255, 255, 255, 0)',
            //storing the locally generated image
            avatar: null,
            //storing the src to img file
            profile_photo: null,
            //in order to store the unsaved image
            items:[
                {
                    image: false
                }
            ],
            //storing the possible business stream values
            business_items:[],
            //search value
            search: null,
            //storing existing business
            removed_business: [],
            //shows the action type
            actionCreate: false,
        }
    },

    methods: {
        updateCompany() {
            this.$v.$touch();
            if (this.$v.$invalid)
            {
                this.submitStatus = 'ERROR';
            }
            else
            {
                // do your submit logic here
                this.submitStatus = 'PENDING';

                this.company.user_id = this.user.id
                this.company.removed_business = this.removed_business
                axios.post("/api/updateCompany", this.company, {
                    headers:{
                        "Authorization" : "Bearer " + localStorage.getItem("token")
                    }
                }).then(response=> {
                    if(response.status === 201){
                        this.image_upload()
                        this.$store.commit("setCompanyData", this.company)
                        this.removed_business = []
                        this.$router.push({name : "profile"})
                    }
                }).catch(err=> {
                    console.log('update', err.response)
                })

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
            }
        },

        createCompany() {
            this.$v.$touch();
            if (this.$v.$invalid)
            {
                this.submitStatus = 'ERROR';
            }
            else
            {
                // do your submit logic here
                this.submitStatus = 'PENDING';

                this.company.user_id = this.user.id
                axios.post("/api/createCompany", this.company, {
                    headers:{
                        "Authorization" : "Bearer " + localStorage.getItem("token")
                    }
                }).then(response=> {
                    if(response.status === 201){
                        this.image_upload()
                        this.$store.commit("setCompanyData", this.company)
                        this.$store.dispatch("loadCompany", {
                            id: this.user.id,
                            token: localStorage.getItem("token")
                        })
                        this.removed_business = []
                        this.$router.push({name : "profile"})
                    }
                }).catch(err=> {
                    console.log('create',err, err.response)
                })

                setTimeout(() => {
                    this.submitStatus = 'OK'
                }, 500);
            }
        },

        image_upload() {
            //uploading image
            let formData = new FormData()
            formData.append("user_id", this.user.id)
            if(this.avatar){
                formData.append("avatar", this.avatar)
                axios.post("api/uploadAvatar", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        "Authorization" : "Bearer " + localStorage.getItem("token")
                    }
                }).then(response=> {
                    this.$store.commit("image_upload")
                }).catch(err=>{
                    console.log("upload", err.response)
                })
            }
        },

        //calling the file picker input
        previewFiles() {
            document.getElementById('photo').click()
        },

        //getting file and validation
        onFileChange(item, e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;

            let photo = files[0]
            if(photo) {
                if(photo.type === "image/jpeg" || photo.type === "image/jpg" || photo.type === "image/png") {
                    this.createImage(item, files[0]);
                }
                else{
                    alert("Некорректный формат фото")
                }
            }
        },

        //creates local url for previewing avatar
        createImage(item, file) {
            var image = new Image();
            var reader = new FileReader();


            reader.onload = (e) => {
                item.image = e.target.result;
                //on success setting the image
                this.company.image = file.name
                this.avatar = file
            };

            reader.readAsDataURL(file);
        },

        //getting the business stream possible values
        getBusinessStream() {
            axios.post(
                '/api/get_business_stream',
                {},
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).then(response => {
                this.business_items = response.data;
            }).catch(error => {
                console.log(error.response.data);
            });
        },
        //searching for business stream values in db
        searchBusinessStream(val) {
            axios.post(
                '/api/search_business_stream',
                {
                    'search': val
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).then(response => {
                this.business_items = response.data;
            }).catch(error => {
                console.log(error.response.data);
            });
        }
    },

    created() {
        this.getBusinessStream()

        this.user = this.$store.getters.getUserData
        //checking if the company data is loaded
        if(!this.$store.getters.getCompanyLoading){
            this.actionCreate = this.$store.getters.getCreateActionCompany
            //check if the action create or not
            if(this.$store.getters.getCreateActionCompany) {
                // action create
                this.actionCreate = true
            }
            else{
                //setting the data to delete
                this.company = this.$store.getters.getCompanyData
                this.profile_photo = this.company.image ? "./storage/" + this.user.id + "_" + this.company.image : "./storage/default.jpg"
                this.removed_business = this.$store.getters.getCompanyData.business_stream
            }
        }
        else{
            this.$router.push({name: "profile"})
        }
    },
    watch: {
        company (val, old) {
            if(old.business_stream){
                if(val.business_stream.length !== old.business_stream.length){
                    if (val.business_stream.length > 10) {
                        this.$nextTick(() => this.company.business_stream.pop());
                    }
                }
            }
        },
        search (val) {
            if (val !== null && val !== '')
            {
                if (val.length > 1) {
                    this.searchBusinessStream(val);
                }
            }
            else {
                this.getBusinessStream();
            }
        }
    },
    validations: {
        company: {
            company_name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            },
            establishment_date: {
                required
            },
            company_website_url: {
                url,
                minLength: minLength(2),
                maxLength: maxLength(500)
            },
            profile_description: {
                required,
                minLength: minLength(2)
            }
        }
    },
    computed: {
        companyNameErrors () {
            const errors = [];
            if (!this.$v.company.company_name.$dirty) return errors;
            !this.$v.company.company_name.minLength && errors.push('Company name must be greater than 2 characters long');
            !this.$v.company.company_name.maxLength && errors.push('Company name must be at most 255 characters long');
            !this.$v.company.company_name.required && errors.push('Company name is required.');
            return errors;
        },
        establishmentDateErrors () {
            const errors = [];
            if (!this.$v.company.establishment_date.$dirty) return errors;
            !this.$v.company.establishment_date.required && errors.push('Establishment date is required.');
            return errors;
        },
        companyWebsiteURLErrors () {
            const errors = [];
            if (!this.$v.company.company_website_url.$dirty) return errors;
            !this.$v.company.company_website_url.minLength && errors.push('URL must be greater than 2 characters long');
            !this.$v.company.company_website_url.maxLength && errors.push('URL must be at most 500 characters long');
            !this.$v.company.company_website_url.url && errors.push('It must be URL.');
            return errors;
        },
        profileDescriptionErrors () {
            const errors = [];
            if (!this.$v.company.profile_description.$dirty) return errors;
            !this.$v.company.profile_description.minLength && errors.push('Profile description must be greater than 2 characters long');
            !this.$v.company.profile_description.required && errors.push('Profile description is required.');
            return errors;
        }
    },

}
</script>

<style scoped>
.card_body {
    margin-bottom: 140px;
}

.right img{
    width: 170px;
    height: 170px;
}

#photo {
    display: none;
}
.profile_image {
    transition: opacity .4s ease-in-out;
}

.profile_image:hover {
    opacity: 0.6;
}

.show-btns {
    color: #6200EA !important;
}

.hover_height{
    height: 100%;
}
</style>
