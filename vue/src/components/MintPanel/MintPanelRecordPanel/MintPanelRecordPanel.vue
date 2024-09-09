<template>
    <div class="details-panel">
        <div class="tabs-container">
            <h1>{{ title }}</h1>
        </div>
        <div class="fields-container">
            <div v-for="(row, i) in props.data.fields" class="row" :key="i + store.view">
                <div v-for="n in store.columns" :key="n - 1">
                    <Field
                        v-if="row[n - 1] && !(row[n - 1].readonly && store.view === 'edit')"
                        :view="store.view"
                        :defs="row[n - 1]"
                        :data="{ bean: store.bean.attributes }"
                        :label="languages.label(row[n - 1].label, modules.currentModule?.name)"
                        v-model="store.bean.attributes[row[n - 1].name]"
                        @update:modelValue="(additionalFields) => store.updateField(row[n - 1].name, additionalFields)"
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
