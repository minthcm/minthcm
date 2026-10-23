<template>
    <v-alert type="success" variant="tonal" class="mb-4 mx-0" density="compact">
        <template #title>
            {{ languages.label('LBL_CRON_INSTRUCTIONS_LINUX', 'Schedulers') || 'To Setup Crontab' }}
        </template>
        <p class="text-body-2 mb-1">
            {{ languages.label('LBL_CRON_LINUX_DESC1', 'Schedulers') || 'In order to run MintHCM Schedulers, edit your web server user\'s crontab file with this command:' }}
        </p>
        <code class="d-block mb-2 pa-1 bg-grey-lighten-4 rounded">sudo crontab -e -u {{ webServerUser }}</code>
        <p class="text-body-2 mb-1">
            {{ languages.label('LBL_CRON_LINUX_DESC2', 'Schedulers') || '... and add the following line to the crontab file:' }}
        </p>
        <code class="d-block mb-2 pa-1 bg-grey-lighten-4 rounded">* * * * * cd {{ documentRoot }}; php -f cron.php > /dev/null 2>&1</code>
        <p class="text-body-2">
            {{ languages.label('LBL_CRON_LINUX_DESC3', 'Schedulers') || 'You should do this only after the installation is concluded.' }}
        </p>
    </v-alert>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'

defineProps<{ module: string }>()

const languages = useLanguagesStore()
const backend = useBackendStore()

const documentRoot = computed(() => backend.initData?.document_root || '')
const webServerUser = computed(() => backend.initData?.web_server_user || 'www-data')
</script>
