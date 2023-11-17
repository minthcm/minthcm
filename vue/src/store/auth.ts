import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useLanguagesStore } from './languages'

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
        const languages = useLanguagesStore()
        try {
            const response = await axios.post('api/login', {
                username,
                password,
                login_language: languages.currentLanguage ?? 'pl_PL',
            })
            if (response.status !== 200) {
                return false
            }
            return true
        } catch {
            return false
        }
    }

    async function logout() {
        const response = await axios.post('api/logout')
        location.href = ''
    }

    return {
        user,
        authenticate,
        logout,
    }
})
