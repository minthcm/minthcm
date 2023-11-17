import { ref, markRaw, Component } from 'vue'
import { defineStore } from 'pinia'
import { useLanguagesStore } from './languages'
import MintPopupConfirm from '@/components/MintPopups/MintPopupConfirm.vue'

export interface Popup {
    title: string
    component: Component
    unclosable?: boolean
    icon?: string
    data?: object
}

export const usePopupsStore = defineStore('popups', () => {
    const popups = ref<Popup[]>([])
    const languages = useLanguagesStore()

    function showPopup(popup: Popup) {
        popups.value.push({
            ...popup,
            component: markRaw(popup.component),
        })
    }

    function closePopup(popup: Popup) {
        popups.value = popups.value.filter((p) => p !== popup)
    }

    function closeAll() {
        popups.value = popups.value.filter((popup) => popup.unclosable)
    }

    function confirm(text: string) {
        return new Promise((resolve) => {
            showPopup({
                title: languages.label('LBL_CONFIRM'),
                unclosable: true,
                component: markRaw(MintPopupConfirm),
                data: {
                    text,
                    onReject: () => resolve(false),
                    onConfirm: () => resolve(true),
                },
            })
        })
    }

    return {
        popups,
        showPopup,
        closePopup,
        closeAll,
        confirm,
    }
})
