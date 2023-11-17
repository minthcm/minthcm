<template>
    <router-link :to="recordUrl" class="relate-field">
        {{ props.data.bean[defs.name] }}
    </router-link>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()

const recordUrl = computed(() => {
    const module = props.defs.module
    const id = props.data.bean[props.defs.id_name]
    return `/modules/${module}/DetailView/${id}`
})
</script>

<style scoped lang="scss">
.relate-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
}
</style>
