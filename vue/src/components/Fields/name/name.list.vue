<template>
    <a v-if="store.mode === 'relate'" class="name-field" @click="choose">
        {{ props.modelValue }}
    </a>
    <router-link v-else-if="hasViewAccess" :to="recordUrl" class="name-field">
        {{ props.modelValue }}
    </router-link>
    <span v-else>
        {{ props.modelValue }}
    </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { FieldProps } from '../Field.model';
import { useListViewStore } from '@/views/ListView/ListViewStore';
import { usePopupsStore } from '@/store/popups';

const props = defineProps<FieldProps>()
const store = useListViewStore()

const hasViewAccess = computed<boolean>(() => {
    return props.data.bean.aclAccess?.view || false
})

const recordUrl = computed(() => {
    const module = props.data?.bean?.module
    const id = props.data?.bean?.id
    if (!module || !id) return ''
    return `/modules/${module}/DetailView/${id}`
})

function choose() {
    if (!store.relatePopup) {
        return
    }
    if (store.relatePopup.data.fieldToNameArray) {
        const nameToValueArray: { [key: string]: string } = {}
        for (const key in store.relatePopup.data.fieldToNameArray) {
            if (['full_name', 'name', 'last_name', 'first_name'].includes(key)) {
                nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] =
                    props.data?.bean?.attributes?.full_name || props.data?.bean?.attributes?.name || props.data?.bean?.attributes?.last_name || props.data?.bean?.attributes?.first_name || ''
            } else if (!nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] && key === 'subpanel_id') {
                nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] = props.data?.bean?.id
            } else {
                nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] = props.data?.bean?.attributes?.[key] ?? ''
            }
        }
        store.relatePopup.data?.onConfirm({ nameToValueArray })
    } else {
        store.relatePopup.data?.onConfirm({ selectionList: [props.data?.bean?.id || ''] })
    }
    usePopupsStore().closePopup(store.relatePopup)
}
</script>

<style scoped lang="scss">
.name-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
}
</style>
