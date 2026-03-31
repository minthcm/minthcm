<template>
    <div class="details-panel" v-if="acl.hasAccess('Files', 'list', true, true)">
        <div class="tabs-container">
            <h1>{{ title }}</h1>
        </div>
        <div class="dropzone-container">
            <MintDropzone :module="url.module" :record="url.record"/>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useModulesStore } from '@/store/modules'
import { FieldVardef } from '@/store/modules'
import { defineProps, ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import MintDropzone from '@/components/MintDropzone/MintDropzone.vue'
import { useUrlStore } from '@/store/url'
import { useACL } from '@/composables/useACL'

interface Props {
    data: {
        fields: Array<Array<FieldVardef>>
    }
}

const props = defineProps<Props>()
const modules = useModulesStore()
const languages = useLanguagesStore()
const url = useUrlStore()
const acl = useACL()

const title = computed(() => {
    return languages.label(props.data?.title ?? 'LBL_FILES', modules.currentModule?.name)
})

</script>

<style scoped lang="scss">
.details-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;

    h1 {
        font-size: 20px;
    }

    .tabs-container {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #dbdbdb;

        .buttons {
            margin-left: auto;
            display: flex;
            gap: 16px;
        }
    }

    .dropzone-container {
        padding: 16px;
        text-align: center;
        border-radius: 16px;
        color: rgb(20, 93, 123);
        margin: 16px;
        display: flex;
    }

}
</style>