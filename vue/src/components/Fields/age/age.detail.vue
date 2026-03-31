<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div :name="props.defs.name">
                {{ parsedDate }}
                <span v-if="props.field.model.isValid">
                    - ({{ age }} {{ languages.label('LBL_YEARS')?.toLowerCase() }})
                </span>
            </div>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import Pencil from '../Pencil.vue'
import { FieldProps } from '../Field.model'
import { MintDate } from '@/composables/useMintDate'

const props = defineProps<FieldProps<MintDate>>()
const languages = useLanguagesStore()

const parsedDate = computed(() => {
    if (!props.field.model.isValid) {
        return ''
    }
    return props.field.model.formatted.user_date
})
const age = computed(() => {
    if (!props.field.model.isValid) {
        return ''
    }
    const age = props.field.model.instance.diffNow('years').years
    return Math.floor(-age)
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>
