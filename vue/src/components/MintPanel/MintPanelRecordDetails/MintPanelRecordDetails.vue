<template>
    <div class="details-panel" :style="{ marginTop: store.bean.syncAttributes.photo ? '50px' : null }">
        <div class="tabs-container">
            <div class="header-container">
                <MintButton icon="mdi-arrow-left" @click="goBack" />
                <v-avatar
                    v-if="store.bean.syncAttributes.photo"
                    class="photo"
                    size="120"
                    :image="`legacy/index.php?entryPoint=download&id=${store.bean.id}_photo&type=Users`"
                    variant="outlined"
                    color="surface"
                />
                <div class="name-container">
                    <div class="module-name">{{ modules?.currentModule?.label }}</div>
                    <div v-if="!store.bean.isNew" class="bean-name">
                        <v-skeleton-loader
                            v-if="store.bean.isRetrieving"
                            type="heading"
                            :width="500"
                        />
                        <div v-if="!store.bean.isRetrieving">{{ store.bean.name }}</div>
                        <MintButton
                            :icon="isFavorite ? 'mdi-heart' : 'mdi-heart-outline'"
                            variant="nav"
                            size="small"
                            @click="
                                isFavorite
                                    ? favorites.removeFromFavorites(store.bean.module, store.bean.id)
                                    : favorites.addToFavorites(store.bean.module, store.bean.id, store.bean.name)
                            "
                        />
                    </div>
                </div>
            </div>
            <div class="buttons">
                <div v-if="store.bean.aclAccess?.edit === true">
                    <MintButton
                        v-if="store.view === 'detail'"
                        class="ml-auto"
                        icon="mdi-pencil"
                        :text="
                            mdAndDown
                                ? ''
                                : `${languages.label('LBL_EDIT_BUTTON_LABEL')} ${languages.label('LBL_DETAILS')}`
                        "
                        @click="edit"
                    />
                    <div class="buttons" v-if="store.view === 'edit'">
                        <MintButton
                            v-if="!store.bean.isSaving"
                            icon="mdi-close"
                            :text="mdAndDown ? '' : languages.label('LBL_CANCEL_BUTTON_LABEL')"
                            @click="cancel"
                        />
                        <MintButton
                            :disabled="!store.bean.isValid || store.bean.isSaving"
                            :icon="!store.bean.isSaving ? 'mdi-check' : ''"
                            :loading="store.bean.isSaving"
                            variant="primary"
                            :text="
                                mdAndDown
                                    ? ''
                                    : languages.label(store.bean.isSaving ? 'LBL_SAVING' : 'LBL_SAVE_BUTTON_LABEL')
                            "
                            @click="save"
                        />
                    </div>
                </div>
                <v-menu v-if="!store.bean.isNew && actions.length" offset="16">
                    <template v-slot:activator="{ props, isActive }">
                        <MintButton
                            class="ml-auto"
                            v-bind="props"
                            :active="isActive"
                            append-icon="mdi-menu-down"
                            :text="mdAndDown ? '' : languages.label('LBL_ESLIST_ACTIONS')"
                        />
                    </template>
                    <MintMenuList :items="/*props.data.actions*/ actions || []" />
                </v-menu>
            </div>
        </div>
        <div v-if="store.view === 'edit' && store.bean.validationError">
            <MintStatusBox type="error">
                {{ languages.label(store.bean.validationError, store.bean.module) }}
            </MintStatusBox>
        </div>
        <div>
            <v-expansion-panels multiple variant="accordion" class="details-accordion" v-model="expandedSections">
                <v-expansion-panel
                    v-for="(section, index) in props.data.sections"
                    :key="index"
                    :class="['mint-panel', section.fields.length <= 0 && 'mint-panel-disabled']"
                    :value="index"
                    bg-color="transparent"
                >
                    <v-expansion-panel-title class="mint-panel-title" hide-actions>
                        <div>
                            <v-icon
                                :icon="expandedSections.includes(index) ? 'mdi-chevron-down' : 'mdi-chevron-right'"
                            />
                            <span>{{
                                languages.label(section.title, modules.currentModule?.name) ??
                                languages.label(section.title) ??
                                section.title ??
                                ''
                            }}</span>
                        </div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text class="fields-container">
                        <div v-for="(row, i) in computeRows(section)" class="row" :key="`${index}-${i}`">
                            <div v-for="n in row.length >= 2 ? 2 : 1" :key="n - 1">
                                <v-skeleton-loader
                                    v-if="store.bean.isRetrieving"
                                    type="list-item-two-line"
                                    :width="150"
                                />
                                <Field
                                    v-if="row[n - 1] 
                                        && !store.bean.logic.hiddenFields.includes(row[n - 1].name)
                                        && !store.bean.isRetrieving"
                                    :view="
                                        store.bean.logic.readonlyFields.includes(row[n - 1].name)
                                            ? 'detail'
                                            : store.view
                                    "
                                    :defs="row[n - 1]"
                                    :data="{ bean: store.bean }"
                                    :label="languages.label(row[n - 1].label, modules.currentModule?.name)"
                                    :options="store.bean.logic.fieldsOptions[row[n - 1].name]"
                                    :required="store.bean.logic.requiredFields.includes(row[n - 1].name)"
                                    :errorMessage="store.bean.errorMessages[row[n - 1].name]"
                                    :isDirty="store.bean.isDirty || store.bean.fields[row[n - 1].name]?.isDirty"
                                    :field="store.bean.fields[row[n - 1].name]"
                                    :modelValue="store.bean.fields[row[n - 1].name]?.model"
                                    @update:modelValue="
                                        (value, additionalFields) =>
                                            store.updateField(row[n - 1].name, value, additionalFields)
                                    "
                                />
                            </div>
                        </div>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted, watch } from 'vue'
import Field from '@/components/Fields/Field.vue'
import { useRouter } from 'vue-router'
import { useFavoritesStore } from '@/store/favorites'
import { FieldVardef } from '@/store/modules'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useModulesStore } from '@/store/modules'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBoxes/MintStatusBox.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import BeanActions from '@/business/BeanActions'
import { useLocalStorageStore } from '@/store/localStorage'
import { useDisplay } from 'vuetify'

interface Props {
    data: {
        sections: Array<{
            title: string
            collapsed?: boolean
            fields: Array<FieldVardef>[]
        }>
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const languages = useLanguagesStore()
const modules = useModulesStore()
const router = useRouter()
const { mdAndDown } = useDisplay()

const favorites = useFavoritesStore()
const storage = useLocalStorageStore()

const expandedSections = computed({
    get: () => {
        if (!storage.hasPanelSections(store.bean.module, 'MintPanelRecordDetails')) {
            return []
        }
        return storage.getPanelSections(store.bean.module, 'MintPanelRecordDetails')
    },
    set: (value: string[]) => storage.setPanelSections(store.bean.module, 'MintPanelRecordDetails', value),
})

const title = computed(() => {
    return languages.label(props.data?.title ?? 'LBL_DETAILS', modules.currentModule?.name)
})

const computeRows = (panel) => {
    const panelFiltered = panel.fields.filter((row) =>
        row.some((field) => !store.bean.logic.hiddenFields.includes(field.name)),
    )

    if (!mdAndDown.value) {
        return panelFiltered
    }

    const singleFieldRows = []
    panelFiltered.forEach((row) => {
        row.forEach((field) => {
            singleFieldRows.push([field])
        })
    })

    return singleFieldRows
}

const edit = () => {
    store.view = 'edit'
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
    replaceViewPath('DetailView', 'EditView')
}

const cancel = () => {
    if (store.bean.isNew) {
        return goBack()
    }
    store.bean.restore()
    store.view = 'detail'
    store.inlineEditField = ''
    store.inlineEditFieldSaving = ''
    replaceViewPath('EditView', 'DetailView')
}

const save = async () => {
    if (store.bean.isSaving) {
        return
    }
    const prevInlineEditField = store.inlineEditField
    if (prevInlineEditField) {
        store.inlineEditFieldSaving = prevInlineEditField
    }
    store.inlineEditField = ''
    const response = await store.bean.save()
    if (response.status) {
        store.view = 'detail'
        store.inlineEditField = ''
        store.inlineEditFieldSaving = ''
    } else {
        store.inlineEditField = prevInlineEditField
    }
    replaceViewPath('EditView', 'DetailView')
}

const replaceViewPath = (needle: string, replacement: string) => {
    const pathSegments = window.location.href.split('/')
    if (pathSegments.includes(needle)) {
        const location = window.location.href.replace(needle, replacement)
        window.history.replaceState(null, '', location)
    }
}

const actions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = []

    props.data.actions?.forEach((action) => {
        const actionName = typeof action === 'string' ? action : action.name
        const actionClass = BeanActions[actionName]
        if (typeof actionClass !== 'function') {
            console.warn(`Action ${actionName} not defined in BeanActions`)
            return
        }
        const optionDefs = typeof action === 'string' ? {} : action
        const actionObject = new actionClass(store.bean, optionDefs)
        if (actionObject.isAvailable()) {
            actions.push(actionObject.toMenuListItem())
        }
    })
    return actions
})

const goBack = () => {
    if (!router.options.history.state.back) {
        return router.push({ name: 'list', params: { module: modules.currentModule?.name } })
    }
    router.back()
}

const isFavorite = computed(() => favorites.isFavorite(store.bean.module, store.bean.id))

onMounted(() => {
    if (storage.hasPanelSections(store.bean.module, 'MintPanelRecordDetails')) {
        return
    }

    let array = []
    const keys = Object.keys(props.data.sections)
    for (let i = 0; i < keys.length; i++) {
        const key = keys[i]
        if (!props.data.sections[key].collapsed) {
            array.push(key)
        }
    }
    expandedSections.value = array
})

watch(
    () => [store.bean.errorMessages, store.view],
    ([newError, newView]) => {
        if (store.view === 'edit') {
            const errorFields = Object.keys(newError)
            const sectionsToExpand = [] as string[]
            Object.keys(props.data.sections).forEach((key) => {
                const section = props.data.sections[key]
                const sectionFieldNames = section.fields.flat().map((field) => field.name)
                if (sectionFieldNames.some((name) => errorFields.includes(name))) {
                    sectionsToExpand.push(key)
                }
            })
            expandedSections.value = Array.from(new Set([...expandedSections.value, ...sectionsToExpand]))
        }
    },
)
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
            display: flex;
            gap: 16px;
            justify-content: end;
        }

        > *:first-child {
            flex: 1;
        }
    }

    .fields-container {
        display: flex;
        flex-direction: column;

        .row {
            display: flex;
            gap: 24px;

            > * {
                flex: 1;
            }

            margin-bottom: 24px;
        }
    }
}

.details-accordion {
    :deep(.v-expansion-panel__shadow) {
        display: none;
    }
}

.mint-panel {
    .mint-panel-title {
        color: rgb(var(--v-theme-secondary));
        font-size: 15px;
        font-weight: 600;
        padding: 16px;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        padding: 12px 16px;

        > div {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    }

    .mint-panel-content {
        :deep(.v-expansion-panel-text__wrapper) {
            padding: 0px;
        }
    }
}

.header-container {
    display: flex;
    align-items: center;
    gap: 16px;

    .photo {
        margin-top: -75px;
        border: thick solid rgb(var(--v-theme-surface));
    }

    .name-container {
        display: flex;
        flex-direction: column;

        .module-name {
            font-size: 12px;
            color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        }

        .bean-name {
            display: flex;
            gap: 16px;
            align-items: center;
            font-size: 24px;
            font-weight: 600;
            line-height: 1;
        }
    }
}
</style>
