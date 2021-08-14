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
            label="Add some skills"
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
    name: "Skills",
    props: ['editorMode', 'currentSkillArray'],
    data() {
        return {
            items: [],
            model: [],
            search: null,
            stopSearch: false,
        };
    },
    created() {
        this.getSkills();
        if (this.editorMode === 1) {this.model = this.currentSkillArray;}
    },
    watch: {
        model (val) {
            if (val.length > 30) {
                this.$nextTick(() => this.model.pop());
            }
            this.$emit('setSkills', val);
        },
        search (val, old) {
            if (val !== null && val !== '')
            {
                if (val.length > 1)
                {
                    if (!this.stopSearch)
                    {
                        this.searchSkills(val);
                        if (!this.items.length)
                        {
                            this.stopSearch = true;
                        }
                    }
                    else
                    {
                        if (!(old.length < val.length) && !val.substring(0, old.val) === old)
                        {
                            this.stopSearch = false;
                        }
                    }
                }
            }
            else
            {
                this.getSkills();
            }
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
        }
    },
}
</script>

<style scoped>

</style>
