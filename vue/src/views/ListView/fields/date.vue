<template>
    <v-menu v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
        <template v-slot:activator="{ props }">
            <v-text-field
                v-model="formattedDate"
                variant="outlined"
                :label="input.label"
                prepend-inner-icon="mdi-calendar"
                v-bind="props"
                autocomplete="off"
                :error="!isValidDate"
                hide-details
            />
        </template>
        <VueDatePicker
            @update:model-value="(val) => (value = DateTime.fromJSDate(val).toSQLDate())"
            inline
            :enable-time-picker="false"
            :format="format"
        />
        <!-- <v-date-picker
            v-model="input.value"
            no-title
            @input="menu = false"
            locale="pl"
            first-day-of-week="1"
            show-week
            locale-first-day-of-year="4"
            color="rgba(0, 0, 0, 0.4)"

        /> -->
    </v-menu>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { defineProps, ref, computed, watch, defineEmits } from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const emit = defineEmits(['update:modelValue'])
const props = defineProps(['input'])
const value = ref(props.input?.value)
const menu = ref(false)
const isValidDate = computed(() => {
    return !value.value || value.value.length === 10
})

const formattedDate = computed({
    get() {
        if (value.value) {
            const date = format(new Date(value.value))
            return date ?? ''
        }
        return ''
    },
    set(newValue) {
        const date = DateTime.fromFormat(newValue, 'dd.MM.yyyy').toSQLDate()
        if (date) {
            value.value = DateTime.fromFormat(newValue, 'dd.MM.yyyy').toSQLDate()
        }
    },
})

function format(date: Date) {
    const dt = DateTime.fromJSDate(date)
    return dt.toFormat('dd.MM.yyyy')
}

watch(value, () => {
    if (isValidDate.value) {
        emit('update:modelValue', value.value)
    }
})
// export default {
//     props: {
//         input: { type: Object }
//     },
//     data: () => ({
//         menu: false
//     }),
//     computed: {
//         isValidDate() {
//             return !this.input.value || this.input.value.length === 10
//         }
//     }
// }
</script>

<style></style>
