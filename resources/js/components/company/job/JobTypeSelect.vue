<template>
    <div>
        <v-row>
            <v-col>
                <h2>Job Type</h2>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-combobox
                    v-model="select"
                    :items="items"
                    item-text="name"
                    item-value="id"
                    label="Combobox"
                    multiple
                    outlined
                    dense
                ></v-combobox>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "JobType",
    data() {
        return {
            items: [],
            select: []
        }
    },
    created() {
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
            console.log(response.data)
            this.items = response.data;
        }).
        catch(error => {
            console.log(error.response.data);
        });
    },
    watch: {
        select: function (val) {
            this.$store.commit('setJPJobTypeArray', val);
        }
    }
}
</script>

<style scoped>

</style>
