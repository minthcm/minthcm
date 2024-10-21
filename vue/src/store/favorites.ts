import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

interface Favorite {
    id: string
    item_summary: string
    item_summary_short: string
    module_name: string
    image: boolean
}

export const useFavoritesStore = defineStore('favorites', () => {
    const favorites = ref<Favorite[]>([])

    async function fetch() {
        const response = await axios.get('api/Favorites')
        favorites.value = response.data
    }

    function removeFromFavorites(module: string, record: string) {
        const index = favorites.value.findIndex((f) => f.module_name === module && f.id === record)
        if (index > -1) {
            favorites.value.splice(index, 1)
        }
    }

    function addToFavorites(module: string, record: string, name: string) {
        favorites.value.push({
            item_summary: name,
            item_summary_short: name.substring(0, 15),
            id: record,
            module_name: module,
            image: false,
        })
    }

    function isFavorite(module: string, record: string): boolean {
        return !!favorites.value.find((f) => f.module_name === module && f.id === record)
    }

    return {
        favorites,
        fetch,
        removeFromFavorites,
        addToFavorites,
        isFavorite,
    }
})
