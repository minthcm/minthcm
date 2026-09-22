import { ref, markRaw, Component } from 'vue'
import { defineStore } from 'pinia'
import { useLanguagesStore } from './languages'
import MintPopupConfirm from '@/components/MintPopups/MintPopupConfirm.vue'
import MintPopupAlert from '@/components/MintPopups/MintPopupAlert.vue'
import MintPopupPrompt from '@/components/MintPopups/MintPopupPrompt.vue'
import MintPopupLoader from '@/components/MintPopups/MintPopupLoader.vue'


export interface Popup {
    title: string
    component: Component
    unclosable?: boolean
    icon?: string
    data?: object
    onClose?: () => void
}

export const usePopupsStore = defineStore('popups', () => {
    const popups = ref<Popup[]>([])
    const languages = useLanguagesStore()

    // Returns the popup as read back from the (reactive) `popups` array — NOT the plain object
    // built here — so that callers that need to close it programmatically (e.g. a loader closed
    // once a request finishes, rather than via user interaction) can pass it straight to
    // `closePopup()`. `popups` is a Vue `ref`, so pushing a plain object and then handing back
    // that same plain reference would silently break `closePopup`'s `p !== popup` identity check
    // — reads through the ref return an auto-unwrapped reactive proxy, which is never `===` the
    // raw object that was pushed.
    function showPopup(popup: Popup): Popup {
        popups.value.push({
            ...popup,
            component: markRaw(popup.component),
        })
        return popups.value[popups.value.length - 1]
    }

    function closePopup(popup: Popup) {
        popup.onClose?.()
        popups.value = popups.value.filter((p) => p !== popup)
    }

    function closeAll() {
        popups.value.filter((popup) => !popup.unclosable).forEach((popup) => popup.onClose?.())
        popups.value = popups.value.filter((popup) => popup.unclosable)
    }

    function confirm(
        text: string,
        options?: {
            closable?: boolean
            title?: string
            confirmLabel?: string
            cancelLabel?: string
            // When given, clicking confirm doesn't close the popup right away: it switches to a
            // blocking spinner (optionally captioned with `loaderLabel`) and awaits `onSubmit`
            // first, only closing (and resolving `true`) once it settles. Lets a caller run the
            // actual request from inside the same popup instead of a separate loader popup.
            onSubmit?: () => Promise<void>
            loaderLabel?: string
        },
    ) {
        return new Promise<boolean>((resolve) => {
            const popup: Popup = {
                title: languages.label(options?.title ?? 'LBL_CONFIRM'),
                unclosable: !options?.closable,
                component: markRaw(MintPopupConfirm),
                data: {
                    text,
                    confirmLabel: languages.label(options?.confirmLabel ?? 'LBL_CONFIRM'),
                    cancelLabel: languages.label(options?.cancelLabel ?? 'LBL_CANCEL'),
                    loaderLabel: options?.loaderLabel,
                    onSubmit: options?.onSubmit,
                    onReject: () => resolve(false),
                    onConfirm: () => resolve(true),
                },
            }
            if (options?.closable) {
                popup.onClose = () => resolve(false)
            }
            showPopup(popup)
        })
    }

    function prompt(
        text: string,
        options?: {
            title?: string
            confirmLabel?: string
            cancelLabel?: string
            required?: boolean
            // See `confirm()`'s `onSubmit` — same idea, called with the trimmed textarea value.
            onSubmit?: (value: string) => Promise<void>
            loaderLabel?: string
        },
    ) {
        return new Promise<string | false>((resolve) => {
            showPopup({
                title: languages.label(options?.title ?? 'LBL_CONFIRM'),
                unclosable: true,
                component: markRaw(MintPopupPrompt),
                data: {
                    text,
                    required: !!options?.required,
                    confirmLabel: languages.label(options?.confirmLabel ?? 'LBL_CONFIRM'),
                    cancelLabel: languages.label(options?.cancelLabel ?? 'LBL_CANCEL'),
                    loaderLabel: options?.loaderLabel,
                    onSubmit: options?.onSubmit,
                    onReject: () => resolve(false),
                    onConfirm: (val: string) => resolve(val),
                },
            })
        })
    }

    // Blocking, unclosable loader popup shown while a request is in flight. Returns the popup
    // entry to pass to `closePopup()` once the request settles — callers should always close it
    // themselves (e.g. in a `finally`), it never resolves/closes on its own.
    function showLoader(text?: string) {
        return showPopup({
            title: languages.label('LBL_LOADING'),
            unclosable: true,
            component: markRaw(MintPopupLoader),
            data: { text },
        })
    }

    function alert(text: string, titleLabel = 'LBL_ALERT', titleLabelModule = '') {
        return new Promise((resolve) => {
            showPopup({
                title: languages.label(titleLabel, titleLabelModule),
                unclosable: true,
                component: markRaw(MintPopupAlert),
                data: {
                    text,
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
        prompt,
        showLoader,
        alert,
    }
})
