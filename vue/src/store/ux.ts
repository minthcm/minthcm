import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { useAuthStore } from './auth'
import DefaultLayout from '@/layouts/DefaultLayout/DefaultLayout.vue'
import GuestLayout from '@/layouts/GuestLayout/GuestLayout.vue'

export const useUxStore = defineStore('ux', () => {
    const loadingScreen = ref(false)
    const drawer = ref(false)
    const route = useRoute()

    function showLoadingScreen() {
        loadingScreen.value = true
    }

    function closeLoadingScreen() {
        loadingScreen.value = false
    }

    const layout = computed(() => {
        if (route.meta?.layout) {
            return route.meta.layout
        }
        const auth = useAuthStore()
        return auth.user?.id && !auth.user.show_login_wizard ? DefaultLayout : GuestLayout
    })

    return {
        loadingScreen,
        showLoadingScreen,
        closeLoadingScreen,
        layout,
        drawer,
    }
})
