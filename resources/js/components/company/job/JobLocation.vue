<template>
    <div>
        <v-row>
            <v-col>
                <h2>Job Location</h2>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-combobox
                    v-model="country"
                    :items="countriesArray"
                    item-text="name"
                    item-value="id"
                    label="Country"
                    clearable
                    dense
                    outlined
                ></v-combobox>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-combobox
                    v-model="state"
                    :items="statesArray"
                    item-text="name"
                    item-value="id"
                    label="State"
                    clearable
                    dense
                    outlined
                ></v-combobox>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-combobox
                    v-model="city"
                    :items="citiesArray"
                    item-text="name"
                    item-value="id"
                    label="City"
                    clearable
                    dense
                    outlined
                ></v-combobox>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="address"
                    label="Address"
                    outlined
                />
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "JobLocation",
    data() {
        return {
            countriesArray: [],
            statesArray: [],
            citiesArray: [],

            country: [],
            state: [],
            city: [],
            address: null
        }
    },
    methods: {
        ajaxCountries() {
            axios.post(
                '/api/get_countries',
                {
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.countriesArray = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        ajaxStates(country_id) {
            axios.post(
                '/api/get_states_by_country',
                {
                    'country_id': country_id
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.statesArray = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        },
        ajaxCities(state_id) {
            axios.post(
                '/api/get_cities_by_state',
                {
                    'state_id': state_id
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.citiesArray = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        }
    },
    created() {
        this.ajaxCountries();
    },
    watch: {
        country: function (val) {
            this.$store.commit('setJPSelectedCountry', val);
            this.ajaxStates(val.id);
        },
        state: function (val) {
            this.$store.commit('setJPSelectedState', val);
            this.ajaxCities(val.id);
        },
        city: function (val) {
            this.$store.commit('setJPSelectedCity', val);
        }
    }
}
</script>

<style scoped>

</style>
