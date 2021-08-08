<template>
    <v-row v-if="row">
        <v-col :cols="cols">
            <v-text-field
                :label="label"
                outlined
                :required="required"
                v-model="value"
            ></v-text-field>
        </v-col>
    </v-row>
    <v-col v-else :cols="cols">
        <v-text-field
            :label="label"
            outlined
            :required="required"
            v-model="value"
        ></v-text-field>
    </v-col>
</template>

<script>
export default {
    name: "TextField",
    props: {
        label: {
            type: String,
            required: true
        },
        row: {
            type: Boolean,
            default: false
        },
        cols: {
            type: Number,
            default: null
        },
        required: {
            type: Boolean,
            default: false
        },
        clear:{
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            value: ''
        }
    },
    watch: {
        value: function () {
            this.$emit('input', this.value);
        }
    }
}
</script>

<style scoped>

</style>
