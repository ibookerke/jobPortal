<template>
    <LocationEditor :cities="cities" v-if="form"></LocationEditor>
    <LocationGrid :cities="cities" :grid_data="grid" v-else></LocationGrid>
</template>

<script>

import LocationEditor from "./LocationEditor";
import LocationGrid from "./LocationGrid";

export default {
    name: "Location",
    data() {
        return {
            form: true,
            grid: {},
            cities: []
        }
    },
    components: {
        LocationEditor,
        LocationGrid
    },

    created() {
        this.cities = this.cities_data
        this.getGrid()
    },

    methods: {
        getGrid(col = "id", order = null, page = 1, perpage = 10) {
            let token = localStorage.getItem("token")

            axios.post("/api/getLocations", {
                column: col
            },{
                headers: {
                    "Authorization" : "Bearer " + token
                }
            }).then(response => {
                console.log("Response: ",  response)
                this.grid = response.data.grid
            }).catch(err=> {
                console.log(err.response)
            })
        }
    },

    computed: {
        cities_data: function () {
            axios.get('/api/get_cities')
                .then(response => {
                    this.cities = response.data
                })
        }
    },
}
</script>

<style scoped>

</style>
