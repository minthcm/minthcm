import { useModulesStore } from '@/store/modules'

type ACLView = 'delete' | 'edit' | 'export' | 'import' | 'list' | 'massupdate' | 'view'

export const useACL = () => {
    const ACL_ALLOW_DEFAULT = 0

    // module access
    const ACL_ALLOW_ENABLED = 89
    const ACL_ALLOW_DISABLED = -98

    // view access
    const ACL_ALLOW_ALL = 90
    const ACL_ALLOW_GROUP = 80
    const ACL_ALLOW_OWNER = 75
    const ACL_ALLOW_NONE = -99

    const modules = useModulesStore()

    function hasAccess(module: string, view: ACLView, is_owner = false, in_group = false): boolean {
        if (!module || !modules.modules[module]?.acl) {
            console.warn(`hasAccess: acl is not defined in module "${module}"`)
            return false
        }
        if (modules.modules[module]?.acl?.length === 0) {
            return true
        }
        const acl = modules.modules[module].acl
        const moduleAccess = acl.access === ACL_ALLOW_DEFAULT ? ACL_ALLOW_ENABLED : acl.access
        if (moduleAccess !== ACL_ALLOW_ENABLED) {
            return false
        }
        const viewAccess = acl[view] === ACL_ALLOW_DEFAULT ? ACL_ALLOW_ALL : acl[view]
        if (typeof viewAccess === 'undefined') {
            console.warn(`hasAccess: undefined acl view "${view}", module: ${module}`)
            return false
        }
        if (
            viewAccess === ACL_ALLOW_ALL ||
            (is_owner && [ACL_ALLOW_OWNER, ACL_ALLOW_GROUP].includes(viewAccess)) ||
            (in_group && viewAccess === ACL_ALLOW_GROUP)
        ) {
            return true
        }
        return false
    }

    return {
        hasAccess,
    }
}
