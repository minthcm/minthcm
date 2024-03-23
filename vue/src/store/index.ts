import resetStore from '@/plugins/reset-store'
import { createPinia } from 'pinia'

const pinia = createPinia()
pinia.use(resetStore)
export default pinia
