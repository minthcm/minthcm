<template>
    <iframe class="legacy-view" :src="legacyUrl" :key="iframeReload" ref="legacyIframe" @load="onIframeLoad" />
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUrlStore } from '@/store/url'
import LegacyEvents from './LegacyEventManager'
import { useMintWallStore } from '@/components/MintWall/MintWallStore'
import { mintApi } from '@/api/api'
import { useBackendStore } from '@/store/backend'
import { useLegacyIframeStore } from '@/store/legacyIframe'
import { useThemeStore } from '@/store/theme'
import { useReturnLocationStore } from '@/store/returnLocation'

const route = useRoute()
const router = useRouter()
const url = useUrlStore()
const wall = useMintWallStore()
const themeStore = useThemeStore()
const returnLocation = useReturnLocationStore()

onMounted(async () => {
    // messages from legacy iframe
    window.addEventListener('message', handleMessageEvent)
    await wall.readNewsAlertsFromLegacy()
})

onBeforeUnmount(() => {
    window.removeEventListener('message', handleMessageEvent)
})

async function handleMessageEvent(e: MessageEvent) {
    if (e.data?.type === 'mint-theme-preference') {
        const value = e.data.preference
        if (typeof value === 'string' && value.length > 0) {
            await themeStore.setPreference(value)
        }
        return
    }

    const eventId = e.data?.eventId
    const eventName = e.data?.eventName
    const eventData = e.data?.data

    if (eventId && eventName) {
        const eventClass = LegacyEvents[eventName]
        if (!eventClass) {
            console.error(`Unknown event name: ${eventName}`)
            return
        }
        const event = new eventClass(eventId, eventData)
        event.resolveEvent()
        return
    }
    if (!e.data || typeof e.data !== 'string' || e.data.slice(0, 4) !== 'http') {
        return
    }
    const path = url.fromLegacyUrl(e.data)
    const resolved = router.resolve(path)
    if (resolved.meta?.auth === false) {
        router.go(0) //refresh
    } else {
        // Remember the legacy view being left behind, so closing the record returns to it instead
        // of falling back to the module list. #191870
        returnLocation.capture({
            path: route.path,
            // Built here rather than by whoever navigates back, because this component is what puts
            // the legacy view state into the address: onIframeLoad writes it with history.pushState,
            // deliberately bypassing the router. So route.fullPath does not know about it - only the
            // live address does.
            routerLocation: window.location.hash.replace(/^#/, '') || route.fullPath,
            recordPath: resolved.path,
        })
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
        const hashes = location.hash.split('#')
        hashes.shift()

        let locationHash = location.hash
        const match = locationHash.match(/#(?!\/)/)
        if (match) {
            locationHash = locationHash.slice(0, match.index)
        }

        new URLSearchParams(locationHash).forEach((val, key) => {
            url.searchParams.set(key, val)
        })
        hashes.forEach((hash) => {
            return hash.split('&').forEach((pair) => {
                const [key, value] = pair.split('=')
                if (key && value) {
                    url.searchParams.set(key, value)
                }
            })
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
const legacyIframe = ref<HTMLIFrameElement | null>(null)
const backend = useBackendStore()

const legacyIframeStore = useLegacyIframeStore()

function pushThemeToIframe() {
    legacyIframe.value?.contentWindow?.postMessage(
        { type: 'mint-theme', theme: themeStore.activeTheme },
        location.origin,
    )
}

watch(() => themeStore.activeTheme, pushThemeToIframe)

function onIframeLoad() {
    const iframe = legacyIframe.value
    if (!iframe) {
        return
    }
    let currentIframeUrl = iframe.contentWindow?.location.href || iframe.src
    reloadModuleMenu()
    if (route.meta?.legacyQueryToHash) {
        const legacyUrlSearchParams = new URLSearchParams(currentIframeUrl.split('?')[1])
        let hash = []
        legacyUrlSearchParams.forEach((value, key) => {
            if (route.meta?.legacyQueryToHash.includes(key)) {
                hash.push(`${key}=${value}`)
            }
        })
        if (hash.length) {
            let locationHash = location.hash
            const match = locationHash.match(/#(?!\/)/)
            if (match) {
                locationHash = locationHash.slice(0, match.index)
            }
            const fullHash = location.pathname + locationHash + `#${hash.join('&')}`
            history.pushState(null, null, fullHash)
        }
    }
    pushThemeToIframe()
}
async function reloadModuleMenu() {
    const iframe = legacyIframe.value
    if (!iframe?.contentWindow) {
        return
    }
    
    const win = iframe.contentWindow

    const currentAction = win.action_sugar_grp1 ?? ''
    const currentModule = win.module_sugar_grp1 ?? ''

    const { prevAction, prevModule } = legacyIframeStore

    if (
        prevAction === 'EditView' &&
        currentAction === 'DetailView' &&
        currentModule === 'Users'
    ) {
        const result = await mintApi.post('init', {}, { rawError: true })
        backend.initData.menu_modules = result.data.menu_modules
    }

    legacyIframeStore.prevAction = currentAction
    legacyIframeStore.prevModule = currentModule
}
</script>

<style scoped lang="scss">
.legacy-view {
    width: 100%;
    height: calc(100vh - var(--v-top-nav-height) - 7px);
    border: none;
}
</style>
