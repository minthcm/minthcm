<template>
    <div class="position-card-panel">
        <h1>{{ languages.label('LBL_USER_POSITION_PANEL') }}</h1>
        <v-expansion-panels v-model="expandedPanels" class="position-panel-accordion" multiple variant="accordion">
            <v-expansion-panel
                bg-color="transparent"
                :class="['position-panel', competencies.length <= 0 && 'position-panel-disabled']"
                :value="'competencies'"
            >
                <v-expansion-panel-title class="position-panel-title" hide-actions>
                    <v-icon
                        :icon="expandedPanels.includes('competencies') ? 'mdi-chevron-down' : 'mdi-chevron-right'"
                    />
                    <div>
                        <span>{{ languages.label('LBL_COMPETENCIES') }}</span>
                    </div>
                </v-expansion-panel-title>
                <v-expansion-panel-text class="position-panel-content">
                    <div class="panel-element" v-for="competency in competencies" :key="competency.id">
                        <h2 class="element-name">{{ competency.name }}</h2>
                        <div 
                            class="element-description"
                            v-for="line in (competency.description || '').split('\n')"
                            :key="line"
                        >{{ line }}</div>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>

            <v-expansion-panel
                bg-color="transparent"
                :class="['position-panel', responsibilities.length <= 0 && 'position-panel-disabled']"
                :value="'responsibilities'"
            >
                <v-expansion-panel-title class="position-panel-title" hide-actions>
                    <v-icon
                        :icon="expandedPanels.includes('responsibilities') ? 'mdi-chevron-down' : 'mdi-chevron-right'"
                    />
                    <div>
                        <span>{{ languages.label('LBL_RESPONSIBILITIES') }}</span>
                    </div>
                </v-expansion-panel-title>
                <v-expansion-panel-text class="position-panel-content">
                    <div class="panel-element" v-for="responsibility in responsibilities" :key="responsibility.id">
                        <h2 class="element-name">{{ responsibility.name }}</h2>
                        <div 
                            class="element-description"
                            v-for="line in (responsibility.description || '').split('\n')"
                            :key="line"
                        >{{ line }}</div>
                    </div>
                </v-expansion-panel-text>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { ref, onMounted } from 'vue'
import { positionsApi } from '@/api/positions.api'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'

const languages = useLanguagesStore()
const store = useRecordViewStore()
const competencies = ref([])
const responsibilities = ref([])
const expandedPanels = ref<string[]>([])

onMounted(async () => {
    competencies.value = (await positionsApi.getCompetencies(store.bean.module, store.bean.id))?.data ?? []
    responsibilities.value = (await positionsApi.getResponsibilities(store.bean.module, store.bean.id))?.data ?? []

    if (competencies.value.length > 0) {
        expandedPanels.value.push('competencies')
    }
    if (responsibilities.value.length > 0) {
        expandedPanels.value.push('responsibilities')
    }
})
</script>

<style scoped lang="scss">
.position-card-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 24px #0099761a;
    transition: all 100ms ease-in-out;

    h1 {
        font-size: 20px;
        padding: 16px 16px;
        border-bottom: 1px solid #dbdbdb;
    }
}
.position-panel-accordion {
    :deep(.v-expansion-panel__shadow) {
        display: none;
    }

    :deep(.v-expansion-panel-title) {
        justify-content: flex-start !important;
    }
}

.position-panel {
    .position-panel-title {
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
            margin-left: 8px;
        }
    }
    .position-panel-content {
        :deep(.v-expansion-panel-text__wrapper) {
            padding: 0px;
            margin-bottom: 16px;
        }
    }
}

.position-panel-disabled {
    pointer-events: none;
    &:hover {
        background: inherit;
    }

    .position-panel-title {
        color: #0004;
    }
}

.position-panel-content {
    padding: 32px;

    .panel-element {
        margin-bottom: 16px;

        .element-name {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .element-description {
            font-size: 14px;
            padding: 2px 0px 0px 8px;
        }

        .element-description:first-child {
            padding: 8px 0px 0px 8px;
        }
    }

    &:last-child {
        margin-bottom: 0;
    }
}
</style>
