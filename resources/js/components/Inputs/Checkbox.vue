<template>
    <v-checkbox
        v-model="value"
        :label="label"
    ></v-checkbox>
</template>

<script>
export default {
    name: "Checkbox",
    props: {
        label: {
            type: String,
            required: true
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            value: 0
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
