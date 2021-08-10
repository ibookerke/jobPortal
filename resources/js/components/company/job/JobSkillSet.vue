<template>
    <div>
        <v-row>
            <v-col>
                <h2>Skills</h2>
            </v-col>
        </v-row>
        <v-combobox
            v-model="model"
            :items="items"
            item-text="name"
            item-value="id"
            :search-input.sync="search"
            hide-selected
            hint="Maximum of 30 tags"
            label="Add some tags"
            multiple
            persistent-hint
            small-chips
        >
            <template v-slot:no-data>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>
                            No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to create a new one
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-combobox>
    </div>
</template>

<script>
export default {
    name: "JobSkillSet",
    data() {
        return {
            model: [],
            items: [],
            search: null
        }
    },
    methods: {
        getSkills() {
            axios.post(
                '/api/get_skills',
                {},
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).then(response => {
                this.items = response.data;
            }).catch(error => {
                console.log(error.response.data);
            });
        },
        searchSkills(val) {
            axios.post(
                '/api/search_skills',
                {
                    'search': val
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).then(response => {
                this.items = response.data;
            }).catch(error => {
                console.log(error.response.data);
            });
        },
        watch: {
            model (val, old) {
                if (val.length > 30) {
                    this.$nextTick(() => this.model.pop());
                }
                // this.$store.commit('setCVSkills', val);
            },
            search (val) {
                if (val !== null && val !== '')
                {
                    if (val.length > 1)
                    {
                        this.searchSkills(val);
                    }
                }
                else
                {
                    this.getSkills();
                }
            }
        },
    }
}
</script>

<style scoped>

</style>
