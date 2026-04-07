import { useMintWallStore } from '@/components/MintWall/MintWallStore'
import MintWall from '@/components/MintWall/MintWall.vue'
import { useACL } from '@/composables/useACL'

export default {
    icon: 'mdi-newspaper-variant',
    component: MintWall,
    label: 'LBL_MINT_WALL',
    isAvaliable: () => {
        return useACL().hasAccess('News', 'list', true)
    },
    badge: () => {
        const store = useMintWallStore()
        return store.badge
    },
}
