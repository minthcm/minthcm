import { ref } from 'vue'
import axios from 'axios'
import { defineStore } from 'pinia'
import { useLanguagesStore } from './languages'
import { mintApi } from '@/api/api'
import { responseErrorHandler } from '@/api/interceptors/response-error-handler'
import { clearWidgetFetchCache } from '@/components/MintSidepanel/useWidgetFetch'

export interface User {
    id: string
    is_admin: boolean
    first_name: string
    last_name: string
    full_name: string
    email: string
    photo?: string
    show_login_wizard: boolean
    preferences: { [key: string]: any }
}

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null)
    // Label of the error to show after authenticate() returned false
    const loginErrorLabel = ref('LBL_MINT4_AUTH_LOGIN_ERROR')

    async function authenticate(username: string, password: string) {
        loginErrorLabel.value = 'LBL_MINT4_AUTH_LOGIN_ERROR'
        if (!username || !password) {
            return false
        }

        const languages = useLanguagesStore()
        try {
            const internalTokenResponse = await mintApi.post('getInternalFrontendToken', {}, { rawError: true });
            if (!internalTokenResponse.data?.client_secret) {
                return false
            }
            const response = await mintApi.post('login', {
                client_secret: internalTokenResponse.data.client_secret ?? '',
                username,
                password,
                login_language: languages.currentLanguage ?? 'pl_PL',
            }, { rawError: true })
            if (response.status !== 200) {
                return false
            }
            return true
        } catch (error) {
            if (axios.isAxiosError(error) && error.response?.status === 429) {
                loginErrorLabel.value = 'LBL_MINT4_AUTH_LOGIN_TOO_MANY_ATTEMPTS'
            }
            return false
        }
    }

    async function logout() {
        const response = await mintApi.post('logout')
        clearWidgetFetchCache()
        if (response.status == 200 && response.data?.redirect_url) {
            location.href = response.data.redirect_url
            return
        }
        location.href = ''
    }


    return {
        user,
        loginErrorLabel,
        authenticate,
        logout,
    }
})
