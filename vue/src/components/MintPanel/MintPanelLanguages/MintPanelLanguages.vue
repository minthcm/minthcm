<template>
    <div class="languages-panel">
        <div class="panel-header">
            <div class="panel-title">{{ languages.label('LBL_MANAGE_LANGUAGES', 'Administration') }}</div>
            <div class="buttons">
                <MintButton
                    variant="primary"
                    :loading="saving"
                    :text="languages.label('LBL_SAVE_BUTTON_LABEL', 'Administration')"
                    @click="save"
                />
            </div>
        </div>
        <div class="section">
            <v-skeleton-loader v-if="loading" type="table-row@5" />
            <v-table v-else density="compact">
                <thead>
                    <tr>
                        <th>{{ languages.label('LBL_LANGUAGE_PACKS', 'Administration') }}</th>
                        <th>{{ languages.label('LBL_STATUS', 'Administration') }}</th>
                        <th>{{ languages.label('LBL_ACTIONS', 'Administration') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="lang in langs" :key="lang.key">
                        <td>{{ lang.label }}</td>
                        <td>
                            <div class="status-chips">
                                <v-chip
                                    :color="lang.enabled ? 'success' : 'default'"
                                    size="small"
                                >
                                    {{ lang.enabled ? languages.label('LBL_ENABLED', 'Administration') : languages.label('LBL_DISABLED', 'Administration') }}
                                </v-chip>
                                <v-chip v-if="lang.default" color="primary" size="small">
                                    {{ languages.label('LBL_DEFAULT_SYSTEM_LANGUAGE', 'Administration') }}
                                </v-chip>
                            </div>
                        </td>
                        <td>
                            <div v-if="!lang.locked" class="actions">
                                <v-tooltip
                                    location="top"
                                    :text="lang.enabled ? languages.label('LBL_CLICK_TO_DISABLE', 'Administration') : languages.label('LBL_CLICK_TO_ENABLE', 'Administration')"
                                >
                                    <template #activator="{ props: tooltipProps }">
                                        <v-icon
                                            v-bind="tooltipProps"
                                            color="secondary"
                                            size="small"
                                            :icon="lang.enabled ? 'mdi-eye-off' : 'mdi-eye'"
                                            class="action-icon"
                                            v-ripple
                                            @click="toggleLanguage(lang)"
                                        />
                                    </template>
                                </v-tooltip>
                                <v-tooltip
                                    v-if="lang.enabled"
                                    location="top"
                                    :text="languages.label('LBL_SET_AS_DEFAULT_LANGUAGE', 'Administration')"
                                >
                                    <template #activator="{ props: tooltipProps }">
                                        <v-icon
                                            v-bind="tooltipProps"
                                            color="secondary"
                                            size="small"
                                            icon="mdi-star-outline"
                                            class="action-icon"
                                            v-ripple
                                            @click="setDefaultLanguage(lang)"
                                        />
                                    </template>
                                </v-tooltip>
                            </div>
                            <v-tooltip
                                v-else
                                location="top"
                                :text="languages.label('LBL_DEFAULT_LANGUAGE_LOCKED', 'Administration')"
                            >
                                <template #activator="{ props: tooltipProps }">
                                    <v-icon
                                        v-bind="tooltipProps"
                                        icon="mdi-lock"
                                        size="small"
                                        color="disabled"
                                    />
                                </template>
                            </v-tooltip>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { getLanguages, saveLanguages, LanguagePack } from '@/api/adminLanguagesApi'

const languages = useLanguagesStore()
const statusBoxes = useStatusBoxesStore()

const langs = ref<LanguagePack[]>([])
const loading = ref(false)
const saving = ref(false)

async function load() {
    loading.value = true
    try {
        const res = await getLanguages()
        langs.value = res.data as LanguagePack[]
    } catch {
        statusBoxes.showStatus('languages-load-error', {
            type: 'error',
            message: languages.label('LBL_LANGUAGES_LOAD_ERROR', 'Administration'),
            autoClose: true,
            autoCloseDelay: 5000,
        })
    } finally {
        loading.value = false
    }
}

function toggleLanguage(lang: LanguagePack) {
    lang.enabled = !lang.enabled
}

function setDefaultLanguage(lang: LanguagePack) {
    langs.value.forEach((l) => {
        l.default = l.key === lang.key
        l.locked = l.default
    })
}

async function save() {
    if (loading.value || langs.value.length === 0) return
    saving.value = true
    try {
        const disabled = langs.value.filter(l => !l.enabled).map(l => l.key)
        const defaultLanguage = langs.value.find(l => l.default)?.key ?? ''
        await saveLanguages(disabled, defaultLanguage)
        statusBoxes.showStatus('languages-save-success', {
            type: 'success',
            message: languages.label('LBL_SAVED', 'Administration'),
            autoClose: true,
            autoCloseDelay: 3000,
        })
    } catch {
        statusBoxes.showStatus('languages-save-error', {
            type: 'error',
            message: languages.label('ERR_SAVE_RECORD', 'app_strings'),
            autoClose: true,
            autoCloseDelay: 5000,
        })
    } finally {
        saving.value = false
    }
}

onMounted(load)
</script>

<style scoped lang="scss">
.languages-panel {
    background: rgb(var(--v-theme-surface));
    border-radius: 16px;
    box-shadow: 0px 1px 12px #00997619;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: -24px -24px 0;
        padding: 24px 24px 16px;
        border-bottom: 1px solid #dbdbdb;

        .panel-title {
            flex: 1;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.47px;
            color: rgb(var(--v-theme-secondary));
        }
    }

    .section {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .status-chips {
        display: flex;
        gap: 8px;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-icon {
        cursor: pointer;
        border-radius: 50%;
        padding: 8px;
        transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1);

        &:hover {
            background-color: rgba(var(--v-theme-on-surface), 0.04);
        }
    }
}
</style>
