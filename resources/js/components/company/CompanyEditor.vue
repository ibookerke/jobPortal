<template>
    <v-card class="mx-auto pa-4 mt-6 card_body" width="80%">
        <v-form
            ref="form"
            lazy-validation
        >
            <v-row>
                <v-col class="col-9">
                    <v-text-field
                        label="Название"
                        outlined
                        :counter="255"
                        v-model="company.company_name"
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


            <v-btn
                class="mb-4"
                color="success"
                @click="updateCompany"
            >
                Сохранить
            </v-btn>


        </v-form>
        <pre>{{company}}</pre>

    </v-card>
</template>

<script>

export default {
    name: "CompanyEditor",
    data() {
        return {
            user: {},
            //company object
            company: {
                business_stream: null
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
            removed_business: []
        }
    },

    methods: {

        createCompany() {

        },

        //updating company data via api
        updateCompany() {
            let data = this.company
            let formData = new FormData()
            formData.append("user_id", data.user_id)
            formData.append("company_name", data.company_name)
            formData.append("profile_description", data.profile_description)
            formData.append("establishment_date", data.establishment_date)
            formData.append("company_website_url", data.company_website_url)
            formData.append("image", data.image)
            formData.append("avatar", this.avatar)
            formData.append("removed_business", JSON.stringify(this.removed_business))
            formData.append("new_business", JSON.stringify(this.company.business_stream))

            axios.post("/api/updateCompany", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    "Authorization" : "Bearer " + localStorage.getItem("token")
                }
            }).then(response => {
                if(response.status === 200){
                    this.$router.push({name: "profile"})
                    this.$store.commit("setCompanyData", this.company)
                }

            }).catch(err => {
                console.log(err.response)
            })
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
        if(this.$store.getters.getCompanyLoadStatus){
            //getting company data

            this.company = this.$store.getters.getCompanyData
            this.removed_business = this.company.business_stream
            //checking if the company avatar is set
            if(this.company.image === null){
                //if not using the default picture
                this.profile_photo = './storage/default.jpg'
            }
            else{
                //if set getting the picture from storage
                this.profile_photo = './storage/' + this.user.id + '_' + this.company.image
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
