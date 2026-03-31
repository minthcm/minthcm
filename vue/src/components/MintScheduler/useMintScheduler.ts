import { useBean } from '@/composables/useBean'
import { mintApi } from '@/api/api'
import { DateTime } from 'luxon'
import { computed, ref, Ref, toValue, watch } from 'vue'
import { Participant } from './MintScheduler.model'
import { useDebounceFn } from '@vueuse/core'
import { useAuthStore } from '@/store/auth'
import { AxiosError } from 'axios'
import { MintDate } from '@/composables/useMintDate'
import { MintField } from '../Fields/useField'
import { usePreferencesStore } from '@/store/preferences'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { useLanguagesStore } from '@/store/languages'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'

const MAX_HOURS_COUNT = 10

export const useMintScheduler = (
    bean: ReturnType<typeof useBean>,
    dateFrom: Ref<MintField<MintDate> | null>,
    dateTo: Ref<MintField<MintDate> | null>,
) => {
    const auth = useAuthStore()
    const preferences = usePreferencesStore()
    const recordView = useRecordViewStore()

    const activityDtFrom = computed(() => {
        if (!dateFrom.value) {
            return null
        }
        return dateFrom.value.model.isValid ? dateFrom.value.model.instance : null
    })
    const activityDtTo = computed(() => {
        if (!dateTo.value) {
            return null
        }
        return dateTo.value.model.isValid ? dateTo.value.model.instance : null
    })
    const activityFrom = computed(() => {
        if (!activityDtFrom.value) {
            return ''
        }
        return activityDtFrom.value.toFormat('yyyy-MM-dd HH:mm:ss')
    })
    const activityTo = computed(() => {
        if (!activityDtTo.value) {
            return ''
        }
        return activityDtTo.value.toFormat('yyyy-MM-dd HH:mm:ss')
    })

    const schedulerDtFrom = computed(() => {
        if (!activityDtFrom.value) {
            return null
        }
        return activityDtFrom.value.minus({ hours: 2 }).set({ minute: 0, second: 0 })
    })
    const schedulerMaxDtTo = computed(() => {
        if (!schedulerDtFrom.value) {
            return null
        }
        return schedulerDtFrom.value.plus({ hours: MAX_HOURS_COUNT - 1 }).set({ minute: 59, second: 59 })
    })
    const schedulerDtTo = computed(() => {
        if (!activityDtTo.value || !schedulerMaxDtTo.value) {
            return null
        }
        const dt = activityDtTo.value.plus({ hours: 1, minute: 59, seconds: 59 }).set({ minute: 59, second: 59 })
        return dt > schedulerMaxDtTo.value ? schedulerMaxDtTo.value : dt
    })

    const schedulerHours = computed(() => {
        if (!schedulerDtFrom.value) {
            return []
        }
        const hours = []
        const start = schedulerDtFrom.value.setZone(preferences.user?.timezone)
        for (let i = 0; i < MAX_HOURS_COUNT; i++) {
            const dt = start.plus({ hours: i })
            if (schedulerDtTo.value && dt > schedulerDtTo.value) {
                break
            }
            hours.push(start.plus({ hours: i }).toFormat('H') + ':00')
        }
        return hours
    })

    const dateBegin = computed(() => {
        if (!schedulerDtFrom.value) {
            return ''
        }
        return schedulerDtFrom.value.toFormat('yyyy-MM-dd HH:mm:ss')
    })

    const dateEnd = computed(() => {
        if (!schedulerDtTo.value) {
            return ''
        }
        return schedulerDtTo.value.toFormat('yyyy-MM-dd HH:mm:ss')
    })

    const isValid = computed(() => {
        return (
            activityDtFrom.value &&
            activityDtTo.value &&
            activityDtFrom.value < activityDtTo.value &&
            dateBegin.value &&
            dateEnd.value &&
            dateBegin.value < dateEnd.value
        )
    })

    const isEditable = computed(() => {
        return auth.user?.id && (!bean.id || toValue(bean.aclAccess).edit)
    })

    const participants = ref<Participant[]>([])
    const draftParticipants = computed(() => {
        if (bean.id || bean.originalId) {
            return null
        }
        if (!participants.value?.length && auth.user?.id) {
            return [{ module: 'Users', id: auth.user.id }]
        }
        return participants.value.map((p) => ({ module: p.module, id: p.id }))
    })

    const isInitialized = ref(false)
    async function init() {
        if (!bean.id && !bean.originalId && auth.user?.id) {
            bean.loadRelationship('users')?.add(auth.user.id)
        }
        await fetchData()
    }

    async function linkParticipant(participant: Participant) {
        if (bean.id) {
            await mintApi.post(`/${bean.module}/Link/${bean.id}`, {
                ids: [participant.id],
                link_name: participant.link,
            })
            useStatusBoxesStore().showStatus('scheduler-link-success', {
                type: 'success',
                autoClose: true,
                autoCloseDelay: 3000,
                message: useLanguagesStore().label('LBL_SAVED'),
            })
        } else {
            bean.loadRelationship(participant.link)?.add(participant.id)
        }
    }

    let participantsQueue = Promise.resolve()

    function runInQueue(fn: () => Promise<void>) {
        participantsQueue = participantsQueue.then(fn).catch(() => {})
        return participantsQueue
    }

    async function addParticipant(participant: Participant) {
        await runInQueue(async () => {
            if (participants.value.find(p => p.id === participant.id)) {
                return
            }
            participants.value.push(participant)
            await linkParticipant(participant)
            await recordView.fetchSubpanelRecords(participant.link, 10, 0)
        })
    }

    async function removeParticipant(participant: Participant) {
        await runInQueue(async () => { 

            if (!participants.value.find(p => p.id === participant.id)) {
                return
            }

            participants.value = participants.value.filter(p => p.id !== participant.id)

            if (bean.id) {
                await mintApi.post(`/${bean.module}/Unlink/${bean.id}`, {
                    ids: [participant.id],
                    link_name: participant.link,
                })
                useStatusBoxesStore().showStatus('scheduler-unlink-success', {
                    type: 'success',
                    autoClose: true,
                    autoCloseDelay: 3000,
                    message: useLanguagesStore().label('LBL_SAVED'),
                })
            } else {
                bean.loadRelationship(participant.link)?.remove(participant.id)
            }

            await recordView.fetchSubpanelRecords(participant.link, 10, 0)
        })
    }

    let fetchDataController: AbortController | null = null
    async function fetchData() {
        if (fetchDataController) {
            fetchDataController.abort()
        }
        if (!isValid.value) {
            return
        }
        fetchDataController = new AbortController()
        try {
            const result = await mintApi.post<Participant[]>(
                '/scheduler',
                {
                    date_from: dateBegin.value,
                    date_to: dateEnd.value,
                    parent_id: bean.id || bean.originalId || '',
                    parent_type: bean.module,
                    participants: draftParticipants.value,
                },
                {
                    signal: fetchDataController.signal,
                },
            )
            participants.value = result.data
            if (!bean.id && bean.originalId) {
                Object.values(participants.value).forEach((p) => {
                    if (p.id) {
                        linkParticipant(p)
                    }
                })
            }
            isInitialized.value = true
        } catch (error: unknown) {
            if (error instanceof AxiosError) {
                if (error.name === 'CanceledError') {
                    return
                }
            }
            console.error('Failed to fetch scheduler data', error)
        }
    }
    const fetchDataDebounce = useDebounceFn(fetchData, 300, { maxWait: 5000 })
    watch([dateBegin, dateEnd], fetchDataDebounce)

    return {
        bean,
        activityFrom,
        activityTo,
        activityDtFrom,
        activityDtTo,
        schedulerDtFrom,
        schedulerDtTo,
        schedulerHours,
        dateBegin,
        dateEnd,
        isValid,
        isEditable,
        participants,
        init,
        isInitialized,
        addParticipant,
        removeParticipant,
    }
}
