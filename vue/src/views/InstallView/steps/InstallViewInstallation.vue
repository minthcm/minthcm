<template>
    <div class="install-view-installation">
        <v-progress-linear
            color="primary"
            stream
            rounded-bar
            rounded
            height="6"
            style="min-height: 6px"
            :model-value="installationStep"
            :max="21"
        />
        <div class="install-view-installation-steps" ref="stepsContainer">
            <div v-for="(label, step) in status" :key="step" class="install-view-installation-step">
                <v-progress-circular
                    v-if="parseInt(step) === installationStep"
                    indeterminate
                    color="primary"
                    size="20"
                    width="2"
                    style="overflow: hidden"
                />
                <v-icon v-else icon="mdi-check-circle" color="#097c31" size="20" />
                <span class="installation-step-label">{{ label.replaceAll('_', ' ') }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref, onUnmounted, computed, watch } from 'vue'
import { useInstallViewStore } from '../InstallViewStore'
import axios, { AxiosError } from 'axios'
import { nextTick } from 'vue'

const store = useInstallViewStore()

const status = ref({})
const stepsContainer = ref<HTMLElement | null>(null)

const checkStatusInterval = ref<null | number>(null)

defineExpose({
    prevBtn: false,
    nextBtn: false,
})

onMounted(async () => {
    store.install()
    await fetchStatus()
    checkStatusInterval.value = setInterval(async () => {
        await fetchStatus()
    }, 1000)
})

onUnmounted(() => {
    if (checkStatusInterval.value) {
        clearInterval(checkStatusInterval.value)
    }
})

async function fetchStatus() {
    try {
        const response = await axios.get('api/install/status')
        if (Object.keys(response.data || {}).length !== Object.keys(status.value).length) {
            status.value = response.data || {}
        }
    } catch (err) {
        if (err instanceof AxiosError && err.response?.status === 404) {
            // api/install/status returns 404 because htaccess has been replaced
            // replacing htaccess is the last step of installation, so it means, that installation is completed
            store.isInstallationCompleted = true
        }
    }
}

const installationStep = computed(() => {
    if (!Object.keys(status.value ?? {}).length) {
        return 0
    }
    return Object.keys(status.value)
        .map((step) => parseInt(step))
        .sort((a, b) => (a < b ? 1 : a > b ? -1 : 0))[0]
})

watch(
    () => status.value,
    async () => {
        await nextTick()
        stepsContainer.value?.scrollTo({
            behavior: 'smooth',
            left: 0,
            top: stepsContainer.value.scrollHeight,
        })
    },
)
</script>

<style scoped lang="scss">
.install-view-installation {
    display: flex;
    flex-direction: column;
    gap: 24px;
    overflow: hidden;

    .install-view-installation-steps {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-height: 200px;
        max-height: 200px;
        overflow: auto;

        .install-view-installation-step {
            display: flex;
            gap: 16px;
            align-items: center;
            .installation-step-label {
                font-weight: 600;

            }
        }
    }
}
</style>
