<template>
    <router-link
        :name="props.defs.name"
        v-if="hasViewAccess"
        :to="recordUrl"
        :target="store.mode === 'relate' ? '_blank' : null"
        class="relate-field"
        @keydown.space.prevent="handleSpaceNavigation"
    >
        {{ props.modelValue }}
    </router-link>
    <span :name="props.defs.name" v-else>
        {{ props.modelValue }}
    </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useListViewStore } from '@/views/ListView/ListViewStore';
import { FieldProps } from '../Field.model';
import { useACL } from '@/composables/useACL';
import router from '@/router';

const props = defineProps<FieldProps>()
const store = useListViewStore()

function handleSpaceNavigation() {
    if (store.mode === 'relate') {
        window.open(router.resolve(recordUrl.value).href, '_blank')
    } else {
        router.push(recordUrl.value)
    }
}

const hasViewAccess = computed<boolean>(() => {
    return useACL().hasAccess(props.defs.module, 'view', true, true)
})

const recordUrl = computed(() => {
    const module = props.defs.module
    const id = props.data?.bean?.attributes?.[props.defs?.id_name]
    if (!module || !id) return ''
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
