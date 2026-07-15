
import { defineStore } from 'pinia'

export const useLegacyIframeStore = defineStore('legacyIframe', {
    state: () => ({
        prevAction: '',
        prevModule: '',
    }),
})