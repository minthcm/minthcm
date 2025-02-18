<template>
    <div class="parent-container">
    <div>
            <label>{{ languages.label('LBL_ASSIGNED_TO_MODULE') }}</label>
        <div class="detail-field-row">
                <router-link :to="urls.parent" class="relate-field">
                    {{ props.data.bean.parent_type }}
                </router-link>
                <Pencil :defs="props.defs" />
            </div>
        </div>
        <div>
            <label>{{ languages.label('LBL_ASSIGNED_TO_RECORD') }}</label>
            <div class="detail-field-row">
                <router-link :to="urls.record" class="relate-field">
                {{ props.modelValue }}
            </router-link>
            <Pencil
                :defs="props.defs"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const languages = useLanguagesStore()
const urls = computed(() => {
    const recordModule = props.data.bean.parent_type
    const recordId = props.data.bean[props.defs.id_name]
    return { record: `/modules/${recordModule}/DetailView/${recordId}`, parent: `/modules/${recordModule}/ESListView` }
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
.parent-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 24px;
    padding-left: 16px;
    border-left: 1px solid rgb(var(--v-theme-primary-light));
}
</style>
