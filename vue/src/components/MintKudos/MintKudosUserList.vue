<template>
    <div class="header">
        <MintButton @click="store.activeView = 'kudos-list'" icon="mdi-arrow-left" size="large" class="mt-4" />
        <v-text-field
            v-model="searchValue"
            ref="searchInput"
            hide-details
            :placeholder="languages.label('LBL_MINT4_GS_SEARCH_INPUT')"
            variant="plain"
        >
            <template #prepend-inner>
                <v-fab-transition>
                    <v-icon v-if="searchValue" icon="mdi-close" @click="searchValue = ''" />
                    <v-icon v-else icon="mdi-magnify" />
                </v-fab-transition>
            </template>
        </v-text-field>
    </div>
    <v-list lines="one">
        <v-list-item
            v-for="user in filteredUsers"
            :key="user.id"
            @click="openForm(user)"
            class="list-item"
            :title="user.full_name"
            v-bind="{
                [user.photo ? 'prepend-avatar' : 'prepend-icon']: user.photo
                    ? `legacy/index.php?entryPoint=download&type=Users&id=${user.id}_photo`
                    : 'mdi-account',
            }"
        >
        </v-list-item>
    </v-list>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import MintButton from '../MintButtons/MintButton.vue'
import { useMintKudosStore } from './MintKudosStore'
import { useLanguagesStore } from '@/store/languages'
import { MintKudosUser } from './MintKudosStore'

const store = useMintKudosStore()
const languages = useLanguagesStore()
const searchValue = ref('')
const filteredUsers = computed(() =>
    searchValue.value
        ? store.users.filter((user) => user.full_name.toLowerCase().includes(searchValue.value.toLowerCase()))
        : store.users,
)

function openForm(user: MintKudosUser) {
    store.activeView = 'form'
    store.form.user = user
    store.form.text = ''
    store.showUserList = false
}
</script>

<style scoped lang="scss">
.header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 16px;
    padding: 0 8px;
}
.list-item {
    border-bottom: 1px solid #0000001f;
    cursor: pointer;
    &:hover {
        background-color: rgba(var(--v-theme-primary-light));
    }
}
</style>
