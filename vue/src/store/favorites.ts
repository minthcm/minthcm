import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

interface Favorite {
    id: string
    item_summary: string
    module_name: string
}

export const useFavoritesStore = defineStore('favorites', () => {
    const favorites = ref<Favorite[]>([])

    async function fetch() {
        const response = await axios.get('api/Favorites')
        favorites.value = response.data
    }

    function removeFromFavorites(id: string) {
    }

    function addToFavorites(module: string, record: string) {
    }

    return {
        favorites,
        fetch,
        removeFromFavorites,
        addToFavorites,
    }
})
