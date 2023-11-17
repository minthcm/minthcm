import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import AuthViewLogin from './AuthViewLogin.vue'
import AuthViewForget from './AuthViewForget.vue'
import AuthViewReset from './AuthViewReset.vue'

interface FooterNavAction {
    routeName: string
    label: string
}

export const useAuthViewStore = defineStore('authview', () => {
    const views = {
        login: AuthViewLogin,
        forget: AuthViewForget,
        reset: AuthViewReset,
    }

    const username = ref('')
    const view = ref<'login' | 'forget' | 'reset'>('login')
    const footerNavAction = ref<FooterNavAction | null>(null)

    const currentView = computed(() => {
        return views[view.value] ?? views.login
    })

    return {
        username,
        view,
        currentView,
        footerNavAction,
    }
})
