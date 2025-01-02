import { createRouter, createWebHashHistory } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import routes from './routes'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'

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
        return { name: 'auth-login', query: { redirect: to.path } }
    }
    if (auth.user?.show_login_wizard && to.name !== 'setup-wizard') {
        return { name: 'setup-wizard' }
    }
    if (to.meta?.auth === false && auth.user?.id) {
        return { name: 'dashboard' }
    }
    if (to.name === 'list') {
        const module = to.params.module?.toString()
        const legacy_list_params = {
            name: 'module-view',
            params: {
                module,
                action: 'index',
            },
        };
        if (backend.initData?.legacy_views?.[module]?.list) {
            return legacy_list_params;
        }
        if(backend.initData?.legacy_views?.[module]?.list === undefined){
            console.warn('Legacy views not defined for module: ' + module + ". Using legacy list view.");
            return legacy_list_params;
        }
    } else if (to.name === 'record') {
        const module = to.params.module?.toString()
        if (backend.initData?.legacy_views?.[module]?.record) {
            return {
                name: 'module-view',
                params: {
                    module,
                    action: 'DetailView',
                    record: to.params.id,
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

router.afterEach(async (to, from) => {
    const languages = useLanguagesStore()
    const backend = useBackendStore()
    const regex = /\/modules\/([^\/?]+)/
    const match = to.fullPath.match(regex)
    const module = match ? match[1] : ''
    const recordViewStore = useRecordViewStore()
    if (!module && to.name !== 'dashboard') {
        return
    }
    if (module && !languages.languages.modules[module]) {
        await languages.fetchModuleLanguage(module)
    }
    if (to.name === 'dashboard') {
        document.title = `${languages.label('LBL_DASHBOARD')} | ${backend.initData?.systemName}`
    } else if (
        to.name === 'record' &&
        module === recordViewStore.bean?.module_name &&
        languages.languages.modules[module]
    ) {
        document.title = `${recordViewStore.bean?.attributes.name} | ${languages.label('LBL_MODULE_NAME', module)} | ${
            backend.initData?.systemName
        }`
    } else if (languages.languages.modules[module]) {
        document.title = `${languages.label('LBL_MODULE_NAME', module)} | ${backend.initData?.systemName}`
    }
})

export default router
