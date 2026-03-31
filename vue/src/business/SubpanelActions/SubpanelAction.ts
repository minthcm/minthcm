import { MenuListItem } from '@/components/MintMenuList.vue'
import { useLanguagesStore } from '@/store/languages'
import { useACL } from '@/composables/useACL'

interface SubpanelActionOptions {
    title?: string
    icon?: string
    acl?: string[]
}

export abstract class SubpanelAction {
    public static readonly TITLE: string = ''
    public static readonly ACTION_KEY: string = ''
    public static readonly ICON: string = 'mdi-circle-medium'
    public static readonly ACL: string[] = []

    protected bean
    protected subpanel
    protected options: SubpanelActionOptions

    public constructor(bean, subpanel, options: SubpanelActionOptions = {}) {
        this.bean = bean
        this.subpanel = subpanel
        this.options = options
    }

    public abstract execute(): Promise<boolean>

    public isAvailable(): boolean {
        const self = this.constructor as typeof SubpanelAction
        const requiredACL = this.options.acl || self.ACL
        const aclHelper = useACL()
        return requiredACL.every((acl) => aclHelper.hasAccess(this.subpanel.module, acl, true))
    }

    public toMenuListItem(): MenuListItem {
        const languagesStore = useLanguagesStore()
        const self = this.constructor as typeof SubpanelAction
        const actionKey = self.ACTION_KEY + '_' + this.subpanel.key
        return {
            title: languagesStore.label(this.options.title || self.TITLE),
            icon: this.options.icon || self.ICON,
            onClick: () => this.execute(),
            actionKey: self.ACTION_KEY ? actionKey : null
        }
    }
}
