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
                density="compact"
                :disabled="disabled"
            />
        </template>
        <VueDatePicker
            @update:model-value="(val) => (value = DateTime.fromJSDate(val).toSQLDate())"
            inline
            :enable-time-picker="false"
            :format="format"
            :locale="locale"
            :select-text="languages.label('LBL_ESLIST_SELECT_DATE')"
        />
    </v-menu>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { ref, computed, watch } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const languages = useLanguagesStore();
const emit = defineEmits(['update:modelValue'])
const props = defineProps(['input', 'disabled'])
const value = ref(props.input?.value)
const menu = ref(false)
const isValidDate = computed(() => {
    return !value.value || value.value.length === 10
})
const locale = computed(() => languages.currentLanguage.split('_')[0]);

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
</script>

<style></style>
