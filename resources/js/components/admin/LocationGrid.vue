<template>
    <v-card>
        <v-card-title>
            Локации
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
            @click:row="tableSort"
            :hide-default-footer="true"
            :headers="headers"
            :items="items"
        ></v-data-table>
    </v-card>
</template>

<script>
export default {
    name: "LocationGrid",
    props: [
        "grid_data"
    ],
    data () {
        return {
            search: '',
            headers: [
                {
                    text: 'id',
                    align: 'start',
                    value: 'id',
                },
                { text: 'Имя', value: 'name', sortable: false, },
                { text: 'Адрес', value: 'address', sortable: false},
                { text: 'Город', value: 'city', sortable: false},
                { text: "Дата создания", value: "created_at"},
                { text: "Дата последнего обновления", value: "updated_at"}

            ],
            items: [],
        }
    },

    watch: {
        grid_data: function() {
            this.items = this.grid_data
        }
    },

    methods: {
        tableSort(value) {
            console.log(value)

        }
    }
}
</script>

<style scoped>

</style>
