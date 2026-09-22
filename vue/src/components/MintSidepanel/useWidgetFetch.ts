import { ref, type Ref } from 'vue'

const CACHE_TTL_MS = 5 * 60 * 1000

interface CacheEntry<T> {
    data: T
    expiresAt: number
}

const cache = new Map<string, CacheEntry<unknown>>()

/** In-memory only (never persisted), but cleared explicitly on logout as defense in depth. */
export function clearWidgetFetchCache() {
    cache.clear()
}

export function useWidgetFetch<T>(
    fetcher: (id: string) => Promise<T>,
    defaultValue: T,
    cacheKeyFn?: (id: string) => string,
): {
    data: Ref<T>
    isLoading: Ref<boolean>
    hasError: Ref<boolean>
    fetchData: (id: string) => Promise<void>
} {
    const data = ref<T>(defaultValue) as Ref<T>
    const isLoading = ref(false)
    const hasError = ref(false)
    let requestSeq = 0

    async function fetchData(id: string) {
        if (!id) return

        const cacheKey = cacheKeyFn ? cacheKeyFn(id) : null
        const cached = cacheKey ? (cache.get(cacheKey) as CacheEntry<T> | undefined) : undefined
        const hasFreshCache = !!cached && cached.expiresAt > Date.now()

        if (hasFreshCache) {
            data.value = cached!.data
        } else {
            isLoading.value = true
        }

        hasError.value = false

        const requestId = ++requestSeq
        try {
            const result = await fetcher(id)
            if (requestId !== requestSeq) return

            data.value = result
            if (cacheKey) cache.set(cacheKey, { data: result, expiresAt: Date.now() + CACHE_TTL_MS })
        } catch {
            if (requestId !== requestSeq) return

            if (!hasFreshCache) {
                data.value = defaultValue
                hasError.value = true
            }
        } finally {
            if (requestId === requestSeq) isLoading.value = false
        }
    }

    return { data, isLoading, hasError, fetchData }
}
