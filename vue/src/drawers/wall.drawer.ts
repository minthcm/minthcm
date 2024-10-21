import { useMintWallStore } from '@/components/MintWall/MintWallStore'
import MintWall from '@/components/MintWall/MintWall.vue'
import { useACL } from '@/composables/useACL'

export default {
    icon: 'mdi-newspaper-variant',
    component: MintWall,
    isAvaliable: () => {
        return useACL().hasAccess('News', 'list', true)
    },
}
