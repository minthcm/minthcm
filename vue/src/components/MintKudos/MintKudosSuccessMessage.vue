<template>
    <h1 class="text-center mt-4" v-text="languages.label('LBL_KUDOS_SUCCESS')" />
    <p class="text-center">
        {{ languages.label('LBL_KUDOS_SUCCESS_MESSAGE_YOUR_KUDOS_TO') }}
        <span
            @click="store.form.user?.id ? store.openEmployeeDetailView(store.form.user?.id) : ''"
            class="text-secondary font-weight-bold mx-1 user"
            >{{ store.form.user?.full_name }}</span
        >
        {{ languages.label('LBL_KUDOS_SUCCESS_MESSAGE_HAS_BEEN_SENT') }}
    </p>
    <MintButton
        @click="store.closeSuccessMessage"
        :text="languages.label('LBL_KUDOS_SUCCESS_BUTTON')"
        class="mx-auto mt-8"
    />
</template>

<script setup lang="ts">
import MintButton from '../MintButtons/MintButton.vue'
import { useMintKudosStore } from './MintKudosStore'
import { useLanguagesStore } from '@/store/languages'

const store = useMintKudosStore()
const languages = useLanguagesStore()
</script>

<style scoped lang="scss">
.user {
    cursor: pointer;
}
</style>
