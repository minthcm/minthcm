<template>
    <router-link :to="`/modules/${props.data.bean.parent_module}/DetailView/${data.bean.id}`" class="name-field">
        {{ props.data.bean.name }}
    </router-link>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
.name-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
}
</style>
