import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

interface Recent {
    item_id: string
    item_summary: string
    module_name: string
}

export const useRecentsStore = defineStore('recents', () => {
    const recents = ref<Recent[]>([])

    async function fetch() {
        const response = await axios.get('api/Trackers')
        recents.value = response.data
    }

    return {
        recents,
        fetch,
    }
})
