<template>
    <div class="details-panel">
        <div class="tabs-container">
            <h1>{{ title }}</h1>
            <template v-if="store.bean.acl_access?.edit === true">
                <MintButton
                    v-if="store.view === 'detail'"
                    class="ml-auto"
                    icon="mdi-pencil"
                    :text="`${languages.label('LBL_EDIT_BUTTON_LABEL')} ${languages.label('LBL_DETAILS')}`"
                    @click="edit"
                />
                <div class="buttons" v-if="store.view === 'edit'">
                    <MintButton
                        v-if="!saveStatus"
                        icon="mdi-close"
                        :text="languages.label('LBL_CANCEL_BUTTON_LABEL')"
                        @click="cancel"
                    />
                    <MintButton
                        :disabled="!!saveStatus || !store.isBeanChanged"
                        :icon="saveButtonStates[saveStatus].icon"
                        :loading="saveStatus === 'saving'"
                        variant="primary"
                        :text="languages.label(saveButtonStates[saveStatus].text)"
                        @click="save"
                    />
                </div>
            </template>
        </div>
        <div class="fields-container">
            <div v-for="(row, i) in props.data.fields" class="row" :key="i + store.view + store.inlineEditField">
                <div v-for="n in store.columns" :key="n - 1">
                    <Field
                        v-if="row[n - 1] && !(row[n - 1].readonly && store.view === 'edit')"
                        :view="store.inlineEditField === row[n - 1].name ? 'edit' : store.view"
                        :defs="row[n - 1]"
                        :data="{ bean: store.bean.attributes }"
                        :label="languages.label(row[n - 1].label, modules.currentModule?.name)"
                        v-model="store.bean.attributes[row[n - 1].name]"
                        @update:modelValue="(additionalFields) => store.updateField(row[n - 1].name, additionalFields)"
                        @inlineEditBtnClicked="inlineEditBtnClicked"
                        @inlineEditSave="save"
                        @inlineEditCancel="cancel"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref, computed } from 'vue'
import Field from '@/components/Fields/Field.vue'
import { FieldVardef } from '@/store/modules'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useModulesStore } from '@/store/modules'
import MintButton from '@/components/MintButtons/MintButton.vue'

interface Props {
    data: {
        fields: Array<Array<FieldVardef>>
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const languages = useLanguagesStore()
const modules = useModulesStore()

const title = computed(() => {
    return languages.label(props.data?.title ?? 'LBL_DETAILS', modules.currentModule?.name)
})

const saveStatus = ref<'' | 'saving' | 'saved' | 'error'>('')
const saveButtonStates = {
    '': {
        icon: 'mdi-check',
        text: 'LBL_SAVE_BUTTON_LABEL',
    },
    saving: {
        icon: '',
        text: 'LBL_SAVING',
    },
    saved: {
        icon: 'mdi-check',
        text: 'LBL_SAVED',
    },
    error: {
        icon: 'mdi-close',
        text: 'LBL_MINT4_STATUS_BOX_ERROR',
    },
}

const inlineEditBtnClicked = (event: string) => {
    store.inlineEditField = event
}

const edit = () => {
    store.view = 'edit'
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
}

const cancel = () => {
    store.bean.dirtyFields.clear()
    store.bean.attributes = { ...store.bean.syncAttributes }
    store.view = 'detail'
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
    saveStatus.value = ''
}

const save = async () => {
    const prevInlineEditField = store.inlineEditField
    if (prevInlineEditField) {
        store.inlineEditFieldSaving = prevInlineEditField
    }
    store.inlineEditField = ''
    saveStatus.value = 'saving'
    const response = await store.saveBean()
    saveStatus.value = [200, 201].includes(response.status) ? 'saved' : 'error'
    setTimeout(() => {
        if (saveStatus.value === 'saved') {
            store.view = 'detail'
            store.inlineEditField = ''
            store.inlineEditFieldSaving = ''
        } else {
            store.inlineEditField = prevInlineEditField
        }
        saveStatus.value = ''
    }, 2000)
}
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

    .fields-container {
        padding: 24px;
        display: flex;
        gap: 24px;
        flex-direction: column;

        .row {
            display: flex;
            gap: 24px;

            > * {
                flex-basis: calc(100% / 3);
            }
        }
    }
}
</style>
