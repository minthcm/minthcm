import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import SetupWizardCookies from './SetupWizardSteps/SetupWizardCookies.vue'
import SetupWizardUserProfile from './SetupWizardSteps/SetupWizardUserProfile.vue'
import SetupWizardLocaleSettings from './SetupWizardSteps/SetupWizardLocaleSettings.vue'
import { useAuthStore } from '@/store/auth'
import axios from 'axios'
import { useBackendStore } from '@/store/backend'

export const useSetupWizardStore = defineStore('setup-wizard', () => {
    const auth = useAuthStore()
    const backend = useBackendStore()

    const isFinished = ref(false)
    const isLoading = ref(false)

    const setupData = ref({
        first_name: auth.user?.first_name ?? '',
        last_name: auth.user?.last_name ?? '',
        email: auth.user?.email ?? '',
        time_zone: auth.user?.preferences.timezone ?? 'Europe/Warsaw',
        time_format: auth.user?.preferences.date_time_preferences.time ?? backend.initData?.global?.time_format ?? 'H:i',
        date_format: auth.user?.preferences.date_time_preferences.date ?? backend.initData?.global?.date_format ?? 'd.m.Y',
        display_name_format: auth.user?.preferences.name_format ?? backend.initData?.global?.name_format ?? 's f l',
    })

    const steps = [
        {
            title: 'LBL_MINT4_SETUP_WIZARD_COOKIES_TITLE',
            component: SetupWizardCookies,
            backLabel: 'LBL_MINT4_DECLINE',
            nextLabel: 'LBL_MINT4_ACCEPT',
        },
        {
            title: 'LBL_MINT4_SETUP_WIZARD_USER_PROFILE_TITLE',
            component: SetupWizardUserProfile,
        },
        {
            title: 'LBL_MINT4_SETUP_WIZARD_LOCALE_SETTINGS_TITLE',
            component: SetupWizardLocaleSettings,
        },
    ]
    const userInfo = ref(null)

    const currentStepNumber = ref(0)
    const currentStep = computed(() => steps[currentStepNumber.value])

    function prevStep() {
        if (currentStepNumber.value === 0) {
            cancel()
        } else {
            currentStepNumber.value--
        }
    }

    function nextStep() {
        if (currentStepNumber.value === steps.length - 1) {
            finish()
        } else {
            currentStepNumber.value++
        }
    }

    async function cancel() {
        isLoading.value = true
        await auth.logout()
    }

    async function finish() {
        isLoading.value = true
        try {
            const response = await axios.post('api/confirm_login_wizard', setupData.value)
            if (response.status === 200) {
                if (auth.user) {
                    auth.user.show_login_wizard = false
                }
                isFinished.value = true
            }
        } finally {
            isLoading.value = false
        }
    }

    return {
        userInfo,
        isLoading,
        isFinished,
        steps,
        setupData,
        currentStep,
        currentStepNumber,
        prevStep,
        nextStep,
    }
})
