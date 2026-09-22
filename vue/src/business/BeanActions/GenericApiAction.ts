import { reactive } from 'vue'
import { BeanAction } from './BeanAction'
import { evaluateCustomVisibility, CustomVisibilityConfig } from './customVisibility'
import { mintApi } from '@/api/api'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import router from '@/router'
import { MenuListItem } from '@/components/MintMenuList.vue'

type OnSuccess = 'reload' | 'refresh_subpanels' | 'redirect' | 'toast'

interface GenericApiActionOptions {
    key: string
    label: string
    icon?: string
    acl: string
    type?: 'confirm' | 'prompt' | 'default'
    confirm?: {
        title?: string
        body: string
        confirmLabel?: string
        cancelLabel?: string
    }
    required?: boolean
    api_route: string
    customVisibility?: CustomVisibilityConfig
    onSuccess?: OnSuccess
    // Blocking loading UI shown while the request is in flight. Defaults to shown (`true`) — set
    // `false` to skip it (e.g. for actions that resolve fast enough to not need it). For
    // `type: 'confirm'`/`'prompt'`, the confirm/prompt popup itself switches to a spinner and
    // stays open until the request settles; for `type: 'default'` (no popup otherwise), a
    // standalone blocking popup is shown instead.
    loader?: boolean
    // i18n key for the text shown under the loader's spinner. Optional — the loader shows with
    // no text below it when omitted.
    loaderLabel?: string
    [key: string]: any
}

const pendingKeys = reactive(new Set<string>())

export class GenericApiAction extends BeanAction {
    public constructor(bean: ConstructorParameters<typeof BeanAction>[0], options: GenericApiActionOptions) {
        super(bean, { ...options, title: options.label })
    }

    // Reads through the base class's `options` field via a cast rather than redeclaring it —
    // redeclaring a field of the same name in a subclass resets it to `undefined` right after
    // `super()` returns (native JS class-fields semantics, `useDefineForClassFields`), wiping out
    // what `super()` just set.
    private get config(): GenericApiActionOptions {
        return this.options as GenericApiActionOptions
    }

    private get pendingKey(): string {
        return `${this.bean.module}:${this.bean.id}:${this.config.key}`
    }

    // Whether a request for this exact action is currently in flight. Kept separate from
    // isAvailable() so the button stays visible (as a loading/disabled item) instead of
    // disappearing from the menu while the request is pending.
    private get isPending(): boolean {
        return pendingKeys.has(this.pendingKey)
    }

    public isAvailable(): boolean {
        return (
            !!this.bean.id &&
            !!this.bean.aclAccess?.[this.config.acl as keyof typeof this.bean.aclAccess] &&
            evaluateCustomVisibility(this.bean.attributes, this.config.customVisibility)
        )
    }

    public toMenuListItem(): MenuListItem {
        return {
            ...super.toMenuListItem(),
            loading: this.isPending,
            disabled: this.isPending,
        }
    }

    // Fires the actual POST request, tracked via `pendingKeys` (drives the menu item's
    // loading/disabled state) for exactly its duration. Used both directly and as a popup's
    // `onSubmit` (where only the fact that it settles matters — the popup awaits it before
    // closing); `execute()` captures its return value into a local so nothing here is shared
    // mutable instance state that a second concurrent call could clobber.
    private async doRequest(value?: string): Promise<any> {
        pendingKeys.add(this.pendingKey)
        try {
            return await mintApi.post(this.config.api_route, {
                id: this.bean.id,
                module: this.bean.module,
                ...(value !== undefined ? { value } : {}),
            })
        } finally {
            pendingKeys.delete(this.pendingKey)
        }
    }

    public async execute(): Promise<boolean> {
        const languagesStore = useLanguagesStore()
        const popupsStore = usePopupsStore()

        if (this.isPending) {
            // Menu item is rendered disabled while pending, but guard against a click that
            // slips through (e.g. via keyboard) before the disabled state is applied.
            return false
        }

        const showLoader = this.config.loader !== false
        const loaderLabel = this.config.loaderLabel
            ? languagesStore.label(this.config.loaderLabel, this.bean.module)
            : undefined

        let response: any

        if (this.config.type === 'confirm' || this.config.type === 'prompt') {
            const body = languagesStore.label(this.config.confirm?.body, this.bean.module)
            const popupOptions = {
                title: this.config.confirm?.title,
                confirmLabel: this.config.confirm?.confirmLabel,
                cancelLabel: this.config.confirm?.cancelLabel,
            }

            if (this.config.type === 'confirm') {
                // With the loader enabled, `onSubmit` fires the request from *inside* the still-open
                // confirm popup — it switches to a blocking spinner and only closes once the request
                // settles, right before the success/error popup takes its place. With it disabled,
                // the popup closes immediately on confirm (previous behavior) and the request fires
                // below, with no blocking UI at all.
                const proceeded = await popupsStore.confirm(body, {
                    ...popupOptions,
                    onSubmit: showLoader
                        ? async () => {
                              response = await this.doRequest()
                          }
                        : undefined,
                    loaderLabel,
                })
                if (!proceeded) {
                    return false
                }
                if (!showLoader) {
                    response = await this.doRequest()
                }
            } else {
                const result = await popupsStore.prompt(body, {
                    ...popupOptions,
                    required: !!this.config.required,
                    onSubmit: showLoader
                        ? async (value: string) => {
                              response = await this.doRequest(value)
                          }
                        : undefined,
                    loaderLabel,
                })
                if (result === false) {
                    return false
                }
                if (!showLoader) {
                    response = await this.doRequest(result)
                }
            }
        } else {
            // type: 'default' — no confirm/prompt popup to embed the loader into, so fall back to
            // a standalone blocking loader popup (still skippable via `loader: false`).
            const loaderPopup = showLoader ? popupsStore.showLoader(loaderLabel) : null
            try {
                response = await this.doRequest()
            } finally {
                if (loaderPopup) {
                    popupsStore.closePopup(loaderPopup)
                }
            }
        }

        if (!response?.data) {
            // Request failed and the global error handler (response-error-handler.ts)
            // already surfaced a generic technical message without rejecting the promise.
            return false
        }

        await popupsStore.alert(languagesStore.label(response.data.message, this.bean.module))

        if (response.data.success) {
            await this.handleOnSuccess(response.data.action ?? this.config.onSuccess)
        }

        return !!response.data.success
    }

    private async handleOnSuccess(onSuccess?: OnSuccess) {
        switch (onSuccess) {
            case 'reload':
                await this.bean.retrieve()
                break
            case 'refresh_subpanels':
                await useRecordViewStore().fetchSubpanelsData()
                break
            case 'redirect':
                router.push({ name: 'list', params: { module: this.bean.module } })
                break
            case 'toast':
            default:
                break
        }
    }
}
