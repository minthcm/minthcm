<template>
    <div class="mint-popup-repeat">
        <div class="repeat-types">
            <v-radio-group v-model="repeatProps.repeat_type" density="compact">
                <v-row 
                    v-for="repeatType in repeatTypes" 
                    :key="repeatType.key"
                    @click="repeatProps.repeat_type = repeatType.key"
                    style="cursor: pointer"
                >
                    <v-col cols="6">
                        <p>{{ repeatType.value }}</p>
                    </v-col>
                    <v-col cols="6">
                        <v-radio  
                            :key="repeatType.key"
                            :value="repeatType.key"
                            class="radio-button"
                        />
                    </v-col>
                </v-row>
            </v-radio-group>
            <div class="repeat-type-details">
                <v-row class="repeat-interval line-top" v-if="repeatProps.repeat_type == 'custom'">
                    <v-col cols="4">
                        {{ languages.label('LBL_REPEAT_PANEL_REPEAT_EVERY') }}
                    </v-col>
                    <v-col cols="4">
                        <v-number-input
                            v-model="repeatProps.repeat_interval"
                            label=""
                            min="1"
                            variant="outlined"
                            density="compact"
                            hide-details
                            control-variant="split"
                            inset
                            class="number-input"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-select
                            label=""
                            :items="repeatIntervalUnitList"
                            variant="outlined"
                            density="compact"
                            hide-details
                            v-model="repeatProps.repeat_interval_unit"
                            item-title="value"
                            item-value="key"
                        />
                    </v-col>
                </v-row>
                <template v-if="repeatProps.repeat_type === 'custom' && repeatProps.repeat_interval_unit == 'week'">
                    <v-row class="line-top">
                        <v-col cols="12">
                            {{ languages.label('LBL_REPEAT_PANEL_REPEAT_ON') }}
                        </v-col>
                    </v-row>
                    <v-row class="chip-row">
                        <v-col cols="12">
                            <v-chip-group 
                                v-model="repeatProps.repeat_dow"
                                multiple
                            >
                                <v-chip
                                    v-for="calendarDay in calendarDaysShort"
                                    :key="calendarDay.key"
                                    :value="calendarDay.key"
                                >
                                    <b>{{ calendarDay.value }}</b>
                                </v-chip>
                            </v-chip-group>
                        </v-col>
                    </v-row>
                </template>
                <v-row class="line-top" v-if="repeatProps.repeat_type !== ''">
                    <v-col cols="12">
                        {{ languages.label('LBL_REPEAT_PANEL_ENDS') }}
                    </v-col>
                </v-row>
                <v-radio-group 
                    v-if="repeatProps.repeat_type !== ''" 
                    v-model="isCountOrUntil" 
                    density="compact" 
                    style="margin-top: 12px" 
                    :key="isCountOrUntil"
                    class="repeat-radio-group"
                >
                    <v-row>
                        <v-col cols="auto">
                            <v-radio value="count" class="radio-button" key="count" />
                        </v-col>
                        <v-col cols="1">
                            {{ languages.label('LBL_REPEAT_PANEL_AFTER') }}
                        </v-col>
                        <v-col cols="5">
                            <v-number-input
                                v-model="repeatProps.repeat_count"
                                :label="languages.label('LBL_REPEAT_PANEL_OCCURRENCES')"
                                min="1"
                                variant="outlined"
                                density="compact"
                                hide-details
                                control-variant="split"
                                inset
                                class="number-input"
                            />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="auto">
                            <v-radio value="until" class="radio-button" key="until" />
                        </v-col>
                        <v-col cols="1">
                            {{ languages.label('LBL_REPEAT_PANEL_ON') }}
                        </v-col>
                        <v-col cols="5">
                            <MintDateField
                                view="edit"
                                :modelValue="repeatUntilDate"
                                :defs="{ name: 'repeat_until' }"
                                :label="languages.label('LBL_REPEAT_PANEL_DATE')"
                                class="date-input"
                            />
                        </v-col>
                    </v-row>
                </v-radio-group>
            </div>
        </div>
        <div class="mint-popup-repeat-buttons">
            <MintButton 
                variant="text"
                :text="languages.label('LBL_CANCEL')"
                @click="cancelRepeat"
            />
            <MintButton 
                variant="primary"
                :text="languages.label('LBL_SAVE_BUTTON_LABEL')"
                @click="saveRepeat"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { computed, ref, reactive, onMounted, watch } from 'vue'
import MintDateField from '@/components/Fields/date/date.edit.vue'
import { useMintDate } from '@/composables/useMintDate'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { usePreferencesStore } from '@/store/preferences'

interface Props {
    data: {
        bean: any
    }
}

const preferences = usePreferencesStore();
const first_day_of_week = preferences.user?.first_day_of_week || 1

const props = defineProps<Props>()
const emit = defineEmits(['close'])

const languages = useLanguagesStore()

const isCountOrUntil = ref('count')

const repeatProps = ref({
    repeat_type: props.data.bean.fields.repeat_type?.model || '',
    repeat_dow: props.data.bean.fields.repeat_dow?.model ? props.data.bean.fields.repeat_dow?.model.split('') : [],
    repeat_count: props.data.bean.fields.repeat_count?.model || 10,
    repeat_interval: props.data.bean.fields.repeat_interval?.model || 1,
    repeat_interval_unit: props.data.bean.fields.repeat_interval_unit?.model || 'week',
})

const untilModel = props.data.bean.fields.repeat_until?.model
const repeatUntilDate = reactive(
    untilModel && typeof untilModel === 'object'
        ? untilModel
        : useMintDate(untilModel)
)

watch(
    () => repeatUntilDate.formatted?.db_date,
    (newVal) => {
        isCountOrUntil.value = newVal ? 'until' : 'count'
    }
)

const repeatTypes = computed(() => {
    return languages.getList('mint4_repeat_type_list')
})

const calendarDaysShort = computed(() => {
    const days = languages.getList('mint4_repeat_panel_days_short_list')
    const startIndex = days.findIndex(day => day.key == first_day_of_week)
    
    if (startIndex === -1 || startIndex === 0) {
        return days
    }
    
    return [...days.slice(startIndex), ...days.slice(0, startIndex)]
})

const repeatIntervalUnitList = computed(() => {
    return languages.getList('mint4_repeat_panel_interval_unit_list')
})

const cancelRepeat = () => {
    emit('close')
}

const saveRepeat = () => {
    if (isCountOrUntil.value == 'until') {
        props.data.bean.fields.repeat_until.model = repeatUntilDate
        props.data.bean.fields.repeat_count.model = ''
    } else {
        props.data.bean.fields.repeat_count.model = repeatProps.value.repeat_count
        props.data.bean.fields.repeat_until.model = ''
    }

    props.data.bean.fields.repeat_type.model = repeatProps.value.repeat_type

    if (repeatProps.value.repeat_type == 'custom') {
        props.data.bean.fields.repeat_dow.model = repeatProps.value.repeat_dow.toString().replaceAll(',','')
        props.data.bean.fields.repeat_interval.model = repeatProps.value.repeat_interval
        props.data.bean.fields.repeat_interval_unit.model = repeatProps.value.repeat_interval_unit
    } else {
        props.data.bean.fields.repeat_dow.model = ''
        props.data.bean.fields.repeat_interval.model = ''
        props.data.bean.fields.repeat_interval_unit.model = ''
    }
    emit('close')
}

onMounted(() => {
    if (repeatUntilDate.isValid) {
        isCountOrUntil.value = 'until'
    } else {
        isCountOrUntil.value = 'count'
    }
})
</script>

<style scoped lang="scss">
.mint-popup-repeat {
    display: flex;
    flex-direction: column;
    min-width: 500px;
    max-height: 60vh;
    overflow: hidden;
    .mint-popup-repeat-buttons {
        display: flex;
        justify-content: flex-end;
        border-top: thin solid #0002;
        padding-top: 16px;
        padding-right: 16px;
        gap: 8px;
    }
    :deep(.v-checkbox) {
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-label) {
        color: #000000;
    }
    :deep(.mint-date-field-btn) {
        color: rgb(var(--v-theme-secondary)) !important;
        opacity: 1 !important;
        &:hover {
            color: rgb(var(--v-theme-secondary)) !important;
            opacity: 1 !important;
        }
    }
    :deep(.v-chip) {
        color: rgb(var(--v-theme-secondary)) !important;
        background: rgb(var(--v-theme-primary-light)) !important;
        opacity: 1 !important;
        border-radius: 100% !important;
        width: 30px !important;
        height: 30px !important;
        display: inline-flex !important;
        justify-content: center !important;
        align-items: center !important;
        
        .v-chip__underlay,
        .v-chip__overlay {
            opacity: 0 !important;
        }
        
        .v-chip__content {
            color: rgb(var(--v-theme-secondary)) !important;
        }
    }
    :deep(.v-chip--selected) {
        color: rgb(var(--v-theme-primary-light)) !important;
        background: rgb(var(--v-theme-secondary)) !important;
        opacity: 1 !important;
        
        .v-chip__content {
            color: rgb(var(--v-theme-primary-light)) !important;
        }
    }
    .repeat-types {
        max-height: 50vh;
        min-height: 35vh;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 16px;
        padding-top: 16px;
        scrollbar-gutter: stable;
    }
}

.line-top {
    border-top: 1px solid rgba(0, 0, 0, 0.133);
    padding-top: 16px;
}
.chip-row {
    padding-top: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 8px !important;
    
    :deep(.v-col) {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
}

.interval-label {
    margin-top: 12px;
}

.radio-button {
    display: flex;
    justify-content: flex-end;
    color: rgb(var(--v-theme-secondary));
}

.repeat-radio-group .radio-button {
    justify-content: flex-start;
}

.number-input {
    margin-left: 12px;
    :deep(i) {
        color: rgb(var(--v-theme-secondary));
    }
}

</style>

<style lang="scss">
.mint-popup-content:has(.mint-popup-repeat) {
    padding: 0 0 16px 16px !important;
}
</style>
