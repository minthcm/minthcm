import { watchEffect } from 'vue'

/**
 * Runs `fetch()` synchronously during a component's `<script setup>` — i.e. before that
 * component's own template first renders — instead of waiting for `onMounted()`.
 *
 * Vue always runs a component's initial template render before its `onMounted` hook fires.
 * When the template itself triggers equivalent-but-per-item fetches on render (e.g. calling
 * `languages.label()` per row, each lazily fetching its own module's translations), doing the
 * batched/eager fetch in `onMounted` lets those per-item calls win the race every time, silently
 * defeating the batch. `watchEffect()` runs its callback synchronously once on creation — before
 * setup finishes and therefore before the first render — which is what makes this eager, unlike
 * a plain `if (shouldFetch()) fetch()` call that just happens to sit at the top of setup.
 */
export function useEagerFetch(shouldFetch: () => boolean, fetch: () => void) {
    let hasRun = false
    watchEffect(() => {
        if (hasRun || !shouldFetch()) {
            return
        }
        hasRun = true
        fetch()
    })
}
