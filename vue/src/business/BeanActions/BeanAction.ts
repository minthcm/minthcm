import { MenuListItem } from '@/components/MintMenuList.vue'
import { useBean } from '@/composables/useBean'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'

interface BeanActionOptions {
    title?: string
    icon?: string
    acl?: string[]
    skipFields?: string[]  // For Duplicate action - fields to exclude when copying
    [key: string]: any     // Allow other custom options for future actions
}

export abstract class BeanAction {
    public static readonly TITLE: string = ''
    public static readonly ICON: string = 'mdi-circle-medium'
    public static readonly ACL: string[] = []

    protected bean: ReturnType<typeof useBean>
    protected options: BeanActionOptions

    public constructor(bean: ReturnType<typeof useBean>, options: BeanActionOptions = {}) {
        this.bean = bean
        this.options = options
    }

    public abstract execute(): Promise<boolean>

    public isAvailable(): boolean {
        const self = this.constructor as typeof BeanAction
        const requiredACL = this.options.acl || self.ACL
        return requiredACL.every((acl) => !!this.bean.aclAccess?.[acl as keyof typeof this.bean.aclAccess])
    }

    public toMenuListItem(): MenuListItem {
        const languagesStore = useLanguagesStore()
        const self = this.constructor as typeof BeanAction
        return {
            title: languagesStore.label(this.options.title || self.TITLE, this.bean.module),
            icon: this.options.icon || self.ICON,
            onClick: () => this.execute(),
        }
    }
}
