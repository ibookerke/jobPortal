<template>
    <div class="loader" v-if="loading">
        <v-progress-linear
            color="white"
            indeterminate
        ></v-progress-linear>
    </div>
    <v-card v-else class="main_card">
        <v-card-title>
            Резюме
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
            @click:row="open_row"
            :headers="headers"
            :items="grid_data"
            :search="search"
        ></v-data-table>
    </v-card>
</template>

<script>
export default {
    name: "CVs",
    data () {
        return {
            search: '',
            headers: [
                {
                    text: 'id',
                    align: 'start',
                    sortable: false,
                    value: 'id',
                },
                { text: 'firstname', value: 'firstname' , sortable:false},
                { text: 'lastname', value: 'lastname', sortable: false },
                { text: 'salary', value: 'salary' },
                { text: 'phone', value: 'phone' },
                { text: 'gender', value: 'gender' },
                { text: 'date_of_birth', value: 'date_of_birth' },
            ],
            loading: true,
            grid_data: [],
        }
    },
    created() {
        if(this.$store.getters.getGridLoadStatus){
            this.loading = false
            this.grid_data = this.$store.getters.getGrid
        }
        else{
            console.log("lalal")
            this.$store.dispatch("loadGrid")
            this.unwatch = this.$store.watch(
                (state, getters) => getters.getGridLoadStatus,
                (newValue, oldValue) => {
                    console.log("babbab")
                    if(newValue) {
                        this.loading = false
                        this.grid_data = this.$store.getters.getGrid
                    }
                }
            )
        }


    },
    methods: {
        open_row(data) {
            this.$store.commit("setRow", data)
            this.$router.push("/cvs/" + data.id)
        }
    }
}
</script>

<style scoped>
.main_card{
    margin-bottom: 140px;
}
</style>
