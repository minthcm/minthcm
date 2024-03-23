import { computed, ref, watch } from 'vue'
import { defineStore } from 'pinia'
import axios, { AxiosError } from 'axios'
import { INSTALL_CONFIG } from './InstallViewConfig'
import { useRouter } from 'vue-router'

export interface InstallEnvironment {
    [key: string]: {
        label: string
        status: 1 | -1 // 1 => OK, -1 => ERROR
        message: string
    }
}

export const useInstallViewStore = defineStore('install-view', () => {
    const router = useRouter()

    const isLoading = ref(false)
    const isInitialLoading = ref(false)
    const isInstallationCompleted = ref(false)

    const version = ref('')
    const license = ref<null | string>(null)
    const environment = ref<null | InstallEnvironment>(null)
    const isInstalling = ref<boolean>(false)
    const databaseConfig = ref({
        dbname: 'minthcm',
        host: 'localhost',
        port: 3306,
        username: 'root',
        password: '',
        collation: 'utf8mb4_general_ci',
    })
    const elasticConfig = ref({
        host: 'localhost',
        port: 9200,
        username: '',
        password: '',
    })
    const siteConfig = ref({
        url: window.location.href.split('#')[0],
        username: 'admin',
        password: '',
        demodata: false,
    })

    const errorMsg = ref('')

    const currentStepNumber = ref(0)
    const currentStep = computed(() => INSTALL_CONFIG.steps[currentStepNumber.value])

    async function fetchInitialData() {
        isInitialLoading.value = true
        try {
            const response = await axios.get('api/install/init')
            version.value = response.data?.version ?? ''
            license.value = response.data?.license ?? ''
            environment.value = response.data?.environment ?? ''
            isInstalling.value = response.data?.isInstalling ?? false
            isInitialLoading.value = false

            if (response.data?.isInstalling) {
                currentStepNumber.value = INSTALL_CONFIG.steps.length - 1
            }
        } catch (err) {
            if ((err as AxiosError).response?.status === 404) {
                router.push({ name: 'dashboard' })
            }
        }
    }

    async function fetchLicense() {
        const response = await axios.get('api/install/license')
        license.value = response.data?.license ?? ''
    }

    async function recheckEnvironment() {
        isLoading.value = true
        const response = await axios.get('api/install/init')
        if (response.data.environment) {
            environment.value = response.data.environment
        }
        isLoading.value = false
    }

    async function validateDb() {
        isLoading.value = true
        try {
            const response = await axios.post('api/install/validate_db', databaseConfig.value)
            isLoading.value = false
            if (response.data?.status === 0) {
                errorMsg.value = response.data.message
                console.error(response.data.error ?? response.data.message)
                return false
            }
            return true
        } catch (err) {
            console.error(err)
            errorMsg.value = 'Server error'
            isLoading.value = false
            return false
        }
    }

    async function validateElastic() {
        isLoading.value = true
        try {
            const response = await axios.post('api/install/validate_elastic', elasticConfig.value)
            isLoading.value = false
            if (response.data?.status === 0) {
                errorMsg.value = response.data.message
                console.error(response.data.error ?? response.data.message)
                return false
            }
            return true
        } catch (err) {
            console.error(err)
            errorMsg.value = 'Server error'
            isLoading.value = false
            return false
        }
    }

    async function install() {
        if (isInstalling.value == true) {
            return true
        }

        const response = await axios.post('api/install/submit', {
            db: databaseConfig.value,
            elastic: elasticConfig.value,
            site: siteConfig.value,
        })
        if (response.data?.status === 0) {
            errorMsg.value = response.data.message
            console.error(response.data.error ?? response.data.message)
            return false
        }
        isInstallationCompleted.value = true
        return true
    }

    function prevStep() {
        currentStepNumber.value--
    }

    function nextStep() {
        //todo: validate last step
        currentStepNumber.value++
    }

    watch(
        () => currentStepNumber.value,
        () => (errorMsg.value = ''),
    )

    return {
        currentStepNumber,
        currentStep,
        isInitialLoading,
        isLoading,
        isInstallationCompleted,
        version,
        license,
        environment,
        databaseConfig,
        elasticConfig,
        siteConfig,
        errorMsg,
        prevStep,
        nextStep,
        fetchLicense,
        recheckEnvironment,
        fetchInitialData,
        validateDb,
        validateElastic,
        install,
    }
})
