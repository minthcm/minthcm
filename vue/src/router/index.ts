import { createRouter, createWebHashHistory } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import routes from './routes'

const router = createRouter({
    history: createWebHashHistory(window.location.pathname),
    routes,
})

router.beforeEach(async (to, from) => {
    if (to.meta?.entryPoint) {
        return
    }
    const backend = useBackendStore()
    if (!backend.isInstalled) {
        return { name: 'install' }
    }
    const auth = useAuthStore()
    if (!backend.isInit) {
        await backend.init()
    }
    if (to.meta?.auth !== false && !auth.user?.id) {
        return { name: 'auth-login' }
    }
    if (auth.user?.show_login_wizard && to.name !== 'setup-wizard') {
        return { name: 'setup-wizard' }
    }
    if (to.meta?.auth === false && auth.user?.id) {
        return { name: 'dashboard' }
    }
    if (to.name === 'list') {
        const module = to.params.module?.toString()
        if (backend.initData?.legacy_views?.[module]?.list) {
            return {
                name: 'module-view',
                params: {
                    module,
                    action: 'index',
                },
            }
        }
    } else if (to.name === 'record') {
        const module = to.params.module?.toString()
        if (backend.initData?.legacy_views?.[module]?.record) {
            return {
                name: 'module-view',
                params: {
                    module,
                    action: 'DetailView',
                },
            }
        }
    }
})

// router.beforeEach((to, from) => {
//     const backend = useBackendStore()
//     const auth = useAuthStore()
//     // if (backend.initialLoading) {
//     //     return
//     // }
//     if (to.meta?.auth !== false && !auth.user?.id) {
//         return { name: 'auth-login' }
//     }
//     if (to.meta?.auth === false && auth.user?.id) {
//         return { name: 'dashboard' }
//     }
//     if (to.name === 'list') {
//         const module = to.params.module?.toString()
//         if (backend.initData?.legacy_views?.[module]?.list) {
//             return {
//                 name: 'module-view',
//                 params: {
//                     module,
//                     action: 'index',
//                 },
//             }
//         }
//     } else if (to.name === 'record') {
//         const module = to.params.module?.toString()
//         if (backend.initData?.legacy_views?.[module]?.record) {
//             return {
//                 name: 'module-view',
//                 params: {
//                     module,
//                     action: 'DetailView',
//                 },
//             }
//         }
//     }
// })

router.afterEach((to, from) => {
    const languages = useLanguagesStore()
    if (to.params?.module && typeof to.params.module === 'string' && !languages.languages.modules[to.params.module]) {
        languages.fetchModuleLanguage(to.params.module)
    }
    // if (to.meta?.isLegacy && from.meta?.isLegacy) {
    //     router.go(0)
    //     return
    // }
})

export default router
