<template>
    <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="value"
                :label="label"
                :required="required"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="value"
            @input="menu = false"
            :min="min"
            :max="max"
            elevation="15"
        ></v-date-picker>
    </v-menu>
</template>

<script>
export default {
    name: "DateField",
    props: {
        label: {
            type: String,
            required: true
        },
        min: {
            type: String,
            default: null
        },
        max: {
            type: String,
            default: null
        },
        required: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            value: null,
            menu: false
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
