import { LegacyEvent } from '../LegacyEvent'
import { useUrlStore } from '@/store/url'
import { useRouter } from 'vue-router'

export class Redirect extends LegacyEvent {
    protected async execute() {
        const url = this.data
        if (!url || url.slice(0, 4) !== 'http') {
            return false
        }
        const router = useRouter()
        const path = useUrlStore().fromLegacyUrl(url)
        const resolved = router.resolve(path)
        if (resolved.meta?.auth === false) {
            router.go(0) //refresh
        } else {
            router.push(path)
        }
        return true
    }
}
