import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { useAuthStore } from './auth'
import DefaultLayout from '@/layouts/DefaultLayout/DefaultLayout.vue'
import GuestLayout from '@/layouts/GuestLayout/GuestLayout.vue'
import { useDisplay } from 'vuetify'

export const useUxStore = defineStore('ux', () => {
    const loadingScreen = ref(false)
    const drawer = ref<null | string>(null)
    const route = useRoute()
    const { mdAndDown } = useDisplay()
    const sideMenu = ref(!mdAndDown.value)

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

    function showHideSideMenu() {
        sideMenu.value = mdAndDown.value ? !sideMenu.value : true;
    }

    return {
        loadingScreen,
        showLoadingScreen,
        closeLoadingScreen,
        layout,
        drawer,
        showHideSideMenu,
        sideMenu,
    }
})
