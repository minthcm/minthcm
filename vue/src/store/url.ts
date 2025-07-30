import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { defineStore } from 'pinia'

export const useUrlStore = defineStore('url', () => {
    const DEFAULT_MODULE = 'Home'
    const route = useRoute()

    // here the names 'module' 'action' or 'record' do not always correspond to the values they are assigned
    // for now I am not changing anything, but I had a problem with adding a second list view
    // because both in the module constant and action constant the module name was assigned, and in the record constant the action name
    // from what we talked about in EWL it would be nice to do it generically so that it could be done in a relatively easy way
    // add another list view. Only then would it be necessary to somehow return the correct parameter names for the appropriate route

    const path = computed(() => {
        return route.path.split('/')
    })

    const module = computed(() => {
        return route.params?.module || path.value?.[2] || DEFAULT_MODULE
    })

    const action = computed(() => {
        return path.value?.[2]
    })

    const record = computed(() => {
        return path.value?.[3]
    })

    function toLegacyUrl(url: string | URL) {
        return 'legacy/index.php'
        const base = 'legacy/index.php'
        if (typeof url === 'string') {
            url = new URL(url)
        }
        const path = url.hash.split('/')
        const module = path[2]
        const action = path[3]
        const record = path[4]
        const legacyUrl = new URL(url.origin + base)
        if (module) {
            legacyUrl.searchParams.set('module', module)
        }
        if (action) {
            legacyUrl.searchParams.set('action', action)
        }
        if (record) {
            legacyUrl.searchParams.set('record', record)
        }
        url.searchParams.forEach((value, param) => {
            legacyUrl.searchParams.set(param, value)
        })
        return legacyUrl.href
    }

    function fromLegacyUrl(url: string | URL) {
        if (typeof url === 'string') {
            if (['/'].includes(url.slice(0, 1))) {
                return url
            } else if (url.slice(0, 3) !== 'http') {
                url = `${location.origin}/${url}`
            }
            url = new URL(url)
        }
        const pathParams = {
            module: url.searchParams.get('module'),
            action: url.searchParams.get('action'),
            record: url.searchParams.get('record'),
        }
        url.searchParams.delete('module')
        url.searchParams.delete('action')
        url.searchParams.delete('record')
        let path = '/'
        if (pathParams.module) {
            path += `modules/${pathParams.module}`
            if (pathParams.action && pathParams.action !== 'index') {
                path += `/${pathParams.action}`
                if (pathParams.record) {
                    path += `/${pathParams.record}`
                }
            }
        }
        return path + url.search
    }

    return {
        module,
        action,
        record,
        toLegacyUrl,
        fromLegacyUrl,
    }
})
