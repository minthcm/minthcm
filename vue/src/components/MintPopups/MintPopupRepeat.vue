<template>
    <div class="mint-popup-repeat">
        <div class="repeat-types">
            <v-radio-group v-model="repeatProps.repeat_type" density="compact">
                <v-row v-for="repeatType in repeatTypes" :key="repeatType.key">
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
                <v-row>
                    <v-col cols="4">
                        <v-text-field
                            v-if="repeatProps.repeat_type !== ''"
                            v-model="repeatProps.repeat_interval"
                            :label="languages.label('LBL_REPEAT_INTERVAL', currentModule)"
                            type="number"
                            min="1"
                            max="30"
                            variant="outlined"
                            density="compact"
                            hide-details
                        />
                    </v-col>
                    <v-col cols="4">
                        <p class="interval-label">{{ currentInterval }}</p>
                    </v-col>
                </v-row>
                <v-radio-group 
                    v-if="repeatProps.repeat_type !== ''" 
                    v-model="isCountOrUntil" 
                    density="compact" 
                    style="margin-top: 12px" 
                    :key="isCountOrUntil"
                >
                    <v-row>
                        <v-col cols="6">
                            <v-text-field
                                v-model="repeatProps.repeat_count"
                                :label="languages.label('LBL_REPEAT_COUNT', currentModule)"
                                type="number"
                                min="1"
                                variant="outlined"
                                density="compact"
                                hide-details
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-radio value="count" class="radio-button" key="count" />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="6">
                            <MintDateField
                                view="edit"
                                :modelValue="repeatProps.repeat_until"
                                :field="props.data.bean.fields.repeat_until"
                                :label="languages.label('LBL_REPEAT_UNTIL', currentModule)"
                                :defs="props.data.bean.fields.repeat_until?.defs || { name: 'repeat_until', type: 'date' }"
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-radio value="until" class="radio-button" key="until" />
                        </v-col>
                    </v-row>
                </v-radio-group>
                <div v-if="repeatProps.repeat_type === 'Weekly'">
                    <v-checkbox
                        v-for="calendarDay in calendarDaysShort"
                        :key="calendarDay.key"
                        :label="calendarDay.value"
                        :value="calendarDay.key"
                        v-model="repeatProps.repeat_dow"
                        density="compact"
                    />
                </div>
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
import { computed, ref, onMounted } from 'vue'
import MintDateField from '@/components/Fields/date/date.edit.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'

interface Props {
    data: {
        bean: any
    }
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])

const languages = useLanguagesStore()

const isCountOrUntil = ref('count')

const currentModule = ref(props.data.bean.module)

const repeatProps = ref({
    repeat_type: props.data.bean.fields.repeat_type?.model,
    repeat_interval: props.data.bean.fields.repeat_interval?.model ?? '1',
    repeat_dow: props.data.bean.fields.repeat_dow?.model ? props.data.bean.fields.repeat_dow?.model.split('') : [],
    repeat_until: props.data.bean.fields.repeat_until?.model,
    repeat_count: props.data.bean.fields.repeat_count?.model ?? '10',
})

const repeatTypes = computed(() => {
    return languages.getList('repeat_type_dom')
})

const calendarDaysShort = computed(() => {
    return languages.getList('dom_cal_day_short').filter((item) => item.value !== '')
})

const repeatIntervals = computed(() => {
    return languages.getList('repeat_intervals')
})

const currentInterval = computed(() => {
    return repeatIntervals.value.find((interval) => interval.key == repeatProps.value.repeat_type)?.value
})

const cancelRepeat = () => {
    emit('close')
}

const saveRepeat = () => {
    if (isCountOrUntil.value == 'until') {
        props.data.bean.fields.repeat_until.model = repeatProps.value.repeat_until
        props.data.bean.fields.repeat_count.model = ''
    } else {
        props.data.bean.fields.repeat_count.model = repeatProps.value.repeat_count
        props.data.bean.fields.repeat_until.model = ''
    }

    props.data.bean.fields.repeat_type.model = repeatProps.value.repeat_type
    props.data.bean.fields.repeat_interval.model = repeatProps.value.repeat_interval

    if (repeatProps.value.repeat_type == 'Weekly') {
        props.data.bean.fields.repeat_dow.model = repeatProps.value.repeat_dow.toString().replaceAll(',','')
    }
    emit('close')
}

onMounted(() => {
    if (repeatProps.value.repeat_until && repeatProps.value.repeat_until !== '') {
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
    gap: 32px;
    min-width: 450px;
    max-height: 60vh;
    overflow: hidden;
    .mint-popup-repeat-buttons {
        display: flex;
        justify-content: flex-end;
        border-top: thin solid #0002;
        padding-top: 16px;
    }
    :deep(.v-checkbox) {
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-label) {
        color: #000000;
    }
    .repeat-types {
        max-height: 50vh;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: -16px;
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

</style>
