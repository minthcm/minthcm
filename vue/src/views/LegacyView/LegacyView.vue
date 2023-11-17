<template>
    <iframe class="legacy-view" :src="legacyUrl" :key="iframeReload" />
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUrlStore } from '@/store/url'

const route = useRoute()
const router = useRouter()
const url = useUrlStore()
onMounted(() => {
    // messages from legacy iframe
    window.addEventListener('message', handleMessageEvent)
})

onBeforeUnmount(() => {
    window.removeEventListener('message', handleMessageEvent)
})

async function handleMessageEvent(e: MessageEvent) {
    if (!e.data || typeof e.data !== 'string' || e.data.slice(0, 4) !== 'http') {
        return
    }
    const path = url.fromLegacyUrl(e.data)
    const resolved = router.resolve(path)
    if (resolved.meta?.auth === false) {
        router.go(0) //refresh
    } else if (resolved.meta?.isLegacy && resolved.name === 'dashboard') {
        history.replaceState(null, '', resolved.href)
    } else {
        router.push(path)

        if (route.path === path.match(/[^\?]*/i)[0]) {
            // Force iframe reload (necessary e.g. for: QC -> create -> full form -> save)
            iframeReload.value++
        }
    }
}

const legacyUrl = computed(() => {
    const route = useRoute()
    if (route.meta?.legacyUrl) {
        const url = new URL(route.meta.legacyUrl, location.origin + location.pathname)
        new URLSearchParams(location.hash).forEach((val, key) => {
            url.searchParams.set(key, val)
        })
        return url.href
    } else if (route.name === 'module-view') {
        const x = new URL(location.origin + location.pathname)
        if (typeof route.params?.module === 'string') {
            x.searchParams.set('module', route.params.module)
        }
        if (typeof route.params?.action === 'string') {
            x.searchParams.set('action', route.params.action)
        }
        if (route.params?.record && typeof route.params.record === 'string') {
            x.searchParams.set('record', route.params.record)
        }
        Object.keys(route.query)
            .filter((key) => !['module', 'action', 'record'].includes(key))
            .forEach((key) => {
                const value = route.query[key]
                if (value && typeof value === 'string') {
                    x.searchParams.set(key, value)
                }
            })
        return 'legacy/index.php' + x.search
    }
    return 'legacy/index.php' + location.search
})

const iframeReload = ref(0)
</script>

<style scoped lang="scss">
.legacy-view {
    width: 100%;
    height: calc(100vh - var(--v-top-nav-height) - 7px);
    border: none;
}
</style>
