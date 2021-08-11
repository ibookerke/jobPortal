<template>
    <div>
        <v-row>
            <v-col>
                <v-select
                    v-model="select"
                    :items="items"
                    item-text="name"
                    item-value="id"
                    label="Job Type"
                    multiple
                    outlined
                    dense
                    return-object
                ></v-select>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "JobType",
    props: ['editType'],
    data() {
        return {
            items: [],
            select: []
        }
    },
    created() {
        this.getJobTypeArray();
        if (!this.editType)
        {
            this.select = this.$store.getters.getJPJobTypes;
        }
    },
    watch: {
        select: function (value) {
            this.$store.commit('setJPJobTypes', value);
        }
    },
    methods: {
        getJobTypeArray() {
            axios.post(
                '/api/get_job_type_array',
                {
                },
                {
                    headers: {
                        'Authorization': `Bearer ${localStorage.token}`
                    }
                }
            ).
            then(response => {
                this.items = response.data;
            }).
            catch(error => {
                console.log(error.response.data);
            });
        }
    }
}
</script>

<style scoped>

</style>
