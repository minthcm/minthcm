<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <router-link :to="recordUrl" class="relate-field">
                {{ props.modelValue }}
            </router-link>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()

const recordUrl = computed(() => {
    const module = props.data.bean.parent_type
    const id = props.data.bean[props.defs.id_name]
    return `/modules/${module}/DetailView/${id}`
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
.relate-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
    display: block;
    width: fit-content;
}
</style>
