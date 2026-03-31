import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useLanguagesStore } from './languages'
import { mintApi } from '@/api/api'
import { responseErrorHandler } from '@/api/interceptors/response-error-handler'

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

    async function authenticate(username: string, password: string) {
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
        } catch {
            return false
        }
    }

    async function logout() {
        const response = await mintApi.post('logout')
        location.href = ''
    }


    return {
        user,
        authenticate,
        logout,
    }
})
