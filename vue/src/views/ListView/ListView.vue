<template>
    <div v-if="access" :class="`list-view-mode-${store.mode}`">
        <h1 v-if="store.mode === 'list'" v-text="moduleName" />
        <div class="list-view-content">
            <ListViewFilters />
            <ListViewHeader />
            <ListViewTable />
        </div>
    </div>
    <div v-else>
        <span v-text="languages.languages.app_strings?.LBL_MINT4_NO_ACCESS_TO_MODULE" />
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import ListViewHeader from './ListViewHeader.vue'
import ListViewTable from './ListViewTable.vue'
import { useListViewStore, Mode } from './ListViewStore'
import { useUrlStore } from '@/store/url'
import { useLanguagesStore } from '@/store/languages'
import { useACL } from '@/composables/useACL'
import ListViewFilters from './ListViewFilters.vue'

const url = useUrlStore()
const store = useListViewStore()
const languages = useLanguagesStore()

interface Props {
    module?: string
    mode?: Mode
}

const props = withDefaults(defineProps<Props>(), {
    mode: 'list',
})

const module = computed(() => props.module ?? url.module)
const moduleName = computed(() => languages.languages.app_list_strings?.moduleList?.[module.value])
const access = ref(false)

onMounted(async () => {
    access.value = useACL().hasAccess(module.value, 'list', true)
    if (store.module !== module.value) {
        store.$reset()
    }
    store.mode = props.mode
    store.module = module.value
    await store.init()
})

onUnmounted(() => {
    store.$reset()
})

watch(module, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        store.init()
    }
})
</script>

<style scoped lang="scss">
h1 {
    letter-spacing: 1px;
    font-weight: 600;
}
.list-view-mode-list {
    padding: 8px 32px !important;

    .list-view-content {
        margin-top: 4px;
        border-radius: 16px;
        padding-bottom: 8px;
        background: rgb(var(--v-theme-surface));
        box-shadow: 0px 2px 4px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
            0px 4px 5px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
            0px 1px 10px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
    }
}
</style>
