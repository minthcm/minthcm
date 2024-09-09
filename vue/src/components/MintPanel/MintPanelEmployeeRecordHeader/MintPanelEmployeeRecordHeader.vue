<template>
    <v-snackbar v-model="appraisalSnackBar" color="secondary" location="top">
        {{ languages.label('LBL_SUCCESS_ADDING_APPRAISAL_JOB', 'Candidatures') }}
    </v-snackbar>
    <v-dialog v-model="appraisalDialog" width="400">
        <v-card class="appraisal-name">
            <v-card-title>
                <h3>{{ languages.label('LBL_CREATE_APPRAISAL', modules.currentModule?.name) }}</h3>
            </v-card-title>
            <v-card-text>
                <Field
                    :view="'edit'"
                    :defs="{
                        type: 'varchar',
                    }"
                    :label="languages.label('LBL_NAME', modules.currentModule?.name)"
                    v-model="appraisalName"
                />
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <MintButton
                    :text="languages.label('LBL_CANCEL_BUTTON_TITLE', modules.currentModule?.name)"
                    @click="appraisalDialog = false"
                />
                <MintButton
                    :disabled="!appraisalName.length"
                    class="ml-2"
                    variant="primary"
                    :text="languages.label('LBL_CREATE_BUTTON_LABEL', modules.currentModule?.name)"
                    @click="createAppraisal"
                />
            </v-card-actions>
        </v-card>
    </v-dialog>
    <div class="header-panel">
        <div class="flex-container">
            <MintButton icon="mdi-arrow-left" @click="goBack" />
            <v-avatar
                v-if="store.bean.syncAttributes.photo"
                class="photo"
                size="120"
                :image="`legacy/index.php?entryPoint=download&id=${store.bean.id}_photo&type=Users`"
                variant="outlined"
                color="surface"
            />
            <v-avatar v-else class="photo" size="120" color="secondary">
                <h1>
                    {{
                        (store.bean.syncAttributes?.first_name?.[0] || '') +
                        (store.bean.syncAttributes?.last_name?.[0] || '')
                    }}
                </h1>
            </v-avatar>
            <div class="name-container">
                <div class="module-name">{{ modules?.currentModule?.label }}</div>
                <div class="bean-name">
                    <div>{{ store.bean.syncAttributes.name }}</div>
                    <MintButton
                        :icon="isFavorite ? 'mdi-heart-circle' : 'mdi-heart-outline'"
                        variant="text"
                        size="small"
                        @click="
                            isFavorite
                                ? favorites.removeFromFavorites(store.bean.module_name, store.bean.id)
                                : favorites.addToFavorites(
                                      store.bean.module_name,
                                      store.bean.id,
                                      store.bean.syncAttributes.name,
                                  )
                        "
                    />
                    <Field
                        v-if="store.bean.syncAttributes.game_score"
                        :view="'detail'"
                        :defs="{ name: 'game_score', type: 'achievements' }"
                        :data="{ bean: store.bean.attributes }"
                        v-model="store.bean.syncAttributes.game_score"
                        @update:modelValue="(newVal) => store.updateField(props.data.fields.game_score.name, newVal)"
                    />
                </div>
            </div>
            <v-menu offset="16">
                <template v-slot:activator="{ props, isActive }">
                    <MintButton
                        class="ml-auto"
                        v-bind="props"
                        :active="isActive"
                        append-icon="mdi-menu-down"
                        :text="languages.label('LBL_ESLIST_ACTIONS')"
                    />
                </template>
                <MintMenuList :items="/*props.data.actions*/ actions || []" />
            </v-menu>
        </div>
        <div v-if="props.data?.fields?.length" class="fields-container">
            <div v-for="(row, i) in props.data.fields" class="row" :key="i">
                <div v-for="n in store.columns" :key="n - 1">
                    <Field
                        v-if="row[n - 1]"
                        :view="'detail'"
                        :defs="row[n - 1]"
                        :label="languages.label(row[n - 1].label, modules.currentModule?.name)"
                        :data="{ bean: store.bean.attributes }"
                        v-model="store.bean.syncAttributes[row[n - 1].name]"
                        @update:modelValue="(newVal) => store.updateField(row[n - 1].name, newVal)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useFavoritesStore } from '@/store/favorites'
import { FieldVardef, useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import Field from '@/components/Fields/Field.vue'
import axios from 'axios'

interface Props {
    data: {
        fields: Array<Array<FieldVardef>>
        actions?: MenuListItem[]
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const router = useRouter()
const route = useRoute()
const favorites = useFavoritesStore()
const modules = useModulesStore()
const languages = useLanguagesStore()

// to change on actions from props (from recordviewdefs)
const actions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = [
        {
            title: languages.label('LBL_CREATE_APPRAISAL', modules.currentModule?.name),
            icon: 'mdi-plus',
            onClick: () => (appraisalDialog.value = true),
        },
        {
            title: 'LNK_VIEW_CHANGE_LOG',
            icon: 'mdi-history',
            onClick: () =>
                window.open(
                    `legacy/index.php?module=Audit&action=Popup&record=${store.bean.id}&module_name=${store.bean.module_name}`,
                    `Audit_popup_window_record_${store.bean.id}_module_name_${store.bean.module_name}`,
                    'width=800,height=800,resizable=1,scrollbars=1',
                ),
        },
    ]
    if (store.bean.acl_access?.delete === true) {
        actions.push({
            title: 'LBL_DELETE_BUTTON_LABEL',
            icon: 'mdi-trash-can-outline',
            onClick: async () => {
                await store.deleteBean()
                router.push({ name: 'list', params: { module: modules.currentModule?.name } })
            },
        })
    }
    return actions
})

const goBack = () => {
    if (!router.options.history.state.back) {
        return router.push({ name: 'list', params: { module: modules.currentModule?.name } })
    }
    router.back()
}
const isFavorite = computed(() => favorites.isFavorite(store.bean.module_name, store.bean.id))
const appraisalDialog = ref<boolean>(false)
const appraisalName = ref<string>('')
const appraisalSnackBar = ref<boolean>(false)
const createAppraisal = async () => {
    appraisalDialog.value = false
    const response = await axios.get(
        `legacy/index.php?entryPoint=scheduleAppraisalAndAppraisalItems&module=${route.params.module}&appraisal_name=${appraisalName.value}&record_id=${route.params.id}`,
    )
    appraisalName.value = ''
    appraisalSnackBar.value = response.status === 200
}
</script>

<style scoped lang="scss">
.v-card.appraisal-name {
    border-radius: 16px !important;
    padding: 16px 24px;
    .v-card-title,
    .v-card-actions {
        padding: 0;
    }
    .v-card-text {
        padding: 24px 0 !important;
    }
}

.header-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;
    margin-top: 50px;

    .flex-container {
        padding: 16px;
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

    .fields-container {
        border-top: 1px solid #dbdbdb;
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
