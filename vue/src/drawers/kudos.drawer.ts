import { useMintKudosStore } from '@/components/MintKudos/MintKudosStore'
import MintKudos from '@/components/MintKudos/MintKudos.vue'
import { useACL } from '@/composables/useACL'

export default {
    icon: 'mdi-thumb-up',
    component: MintKudos,
    onScroll: (drawerElement: HTMLElement) => {
        if (!drawerElement) {
            return
        }
        const store = useMintKudosStore()
        if (
            !store.fetchedAllKudos &&
            drawerElement.scrollTop + drawerElement.clientHeight >= drawerElement.scrollHeight
        ) {
            store.fetchKudos()
        }
    },
    isAvaliable: () => {
        return useACL().hasAccess('Kudos', 'list', true)
    },
}
