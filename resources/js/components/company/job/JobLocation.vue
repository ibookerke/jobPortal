<template>
    <div>
        <v-row>
            <v-col>
                <h2>Job Location</h2>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-select
                    v-model="country"
                    :items="countriesArray"
                    item-text="name"
                    item-value="id"
                    label="Country"
                    clearable
                    dense
                    outlined
                    return-object

                    :error-messages="countryErrors"
                    @select="$v.country.$touch()"
                    @blur="$v.country.$touch()"
                ></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-select
                    v-model="state"
                    :items="statesArray"
                    item-text="name"
                    item-value="id"
                    label="State"
                    clearable
                    dense
                    outlined
                    return-object

                    :error-messages="stateErrors"
                    @select="$v.state.$touch()"
                    @blur="$v.state.$touch()"
                ></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-select
                    v-model="city"
                    :items="citiesArray"
                    item-text="name"
                    item-value="id"
                    label="City"
                    clearable
                    dense
                    outlined
                    return-object

                    :error-messages="cityErrors"
                    @select="$v.city.$touch()"
                    @blur="$v.city.$touch()"
                ></v-select>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-text-field
                    v-model.trim="jobLocation.address"
                    label="Address"
                    outlined

                    :error-messages="addressErrors"
                    :counter="255"
                    @input="$v.jobLocation.address.$touch()"
                    @blur="$v.jobLocation.address.$touch()"
                />
            </v-col>
        </v-row>
    </div>
</template>

<script>
// validation
import { validationMixin } from 'vuelidate';
import { required, minLength, maxLength } from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],
    name: "JobLocation",
    props: ['editType'],
    data() {
        return {
            jobLocation: {},

            countriesArray: [],
            statesArray: [],
            citiesArray: [],

            country: null,
            state: null,
            city: null,

            updateFirstTime: false
        }
    },
    created() {
        let get = this.$store.getters;

        this.ajaxCountries();
        this.jobLocation = get.getJPJobLocation;
        if (!this.editType)
        {
            let locationDetails = get.getJPLocationDetails;
            this.country = locationDetails.country;
            this.state = locationDetails.state;
            this.city = locationDetails.city;
            this.updateFirstTime = true;
        }
    },
    watch: {
        jobLocation: {
            handler: function (value) {
                this.$store.commit('updateJPJobLocation', value);
            },
            deep: true
        },
        country: function (val) {
            this.ajaxStates(val.id);
            this.jobLocation.country_id = val.id;
            if (!this.updateFirstTime)
            {
                this.citiesArray = [];
                this.state = null;
                this.city = null;
            }
        },
        state: function (val) {
            if (!(val === null))
            {
                this.ajaxCities(val.id);
                this.jobLocation.state_id = val.id;
                if (!this.updateFirstTime)
                {
                    this.city = null;
                }
            }
        },
        city: function (val) {
            if (!(val === null))
            {
                this.jobLocation.city_id = val.id;
                if (this.updateFirstTime)
                {
                    this.updateFirstTime = false;
                }
            }
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
    validations: {
        jobLocation: {
            address: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(255)
            }
        },
        country: {
            required
        },
        state: {
            required
        },
        city: {
            required
        }
    },
    computed: {
        addressErrors () {
            const errors = [];
            if (!this.$v.jobLocation.address.$dirty) return errors;
            !this.$v.jobLocation.address.minLength && errors.push('Address must be at least 2 characters long');
            !this.$v.jobLocation.address.maxLength && errors.push('Address must be at most 255 characters long');
            !this.$v.jobLocation.address.required && errors.push('Address is required.');
            return errors;
        },
        countryErrors () {
            const errors = [];
            if (!this.$v.country.$dirty) return errors;
            !this.$v.country.required && errors.push('Country is required.');
            return errors;
        },
        stateErrors () {
            const errors = [];
            if (!this.$v.state.$dirty) return errors;
            !this.$v.state.required && errors.push('State is required.');
            return errors;
        },
        cityErrors () {
            const errors = [];
            if (!this.$v.city.$dirty) return errors;
            !this.$v.city.required && errors.push('City is required.');
            return errors;
        }
    }
}
</script>

<style scoped>

</style>
