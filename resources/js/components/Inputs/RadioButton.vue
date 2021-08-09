<template>
    <v-radio-group v-model="value">
        <template>
            <div>{{ label }}</div>
        </template>
        <v-radio v-for="(item, key) in radios"
                 :label="item"
                 :value="key"
                 :key="key"
        ></v-radio>
    </v-radio-group>
</template>

<script>
export default {
    name: "RadioButton",
    props: {
        label: {
            type: String,
            required: true
        },
        radios: {
            type: [Object, Array],
            required: true
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            value: null
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
