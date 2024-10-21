<template>
    <div class="header">
        <MintButton @click="store.formReset" icon="mdi-arrow-left" size="large" />
        <p v-html="languages.label('LBL_KUDOS_TO')" />
        <div class="photo-container">
            <img
                v-if="store.form?.user?.photo"
                :src="`legacy/index.php?entryPoint=download&type=Users&id=${store.form.user.id}_photo`"
            />
            <v-icon v-else icon="mdi-account" />
        </div>
        <p>
            <span
                :class="{
                    'text-secondary': true,
                    'font-weight-bold': true,
                    'ml-1': store.form?.user?.photo,
                }"
                >{{ store.form?.user?.full_name }}</span
            >
        </p>
    </div>
    <v-container fluid>
        <v-textarea
            counter
            :label="languages.label('LBL_KUDOS_MESSAGE')"
            :rules="rules"
            v-model="store.form.text"
            variant="outlined"
            required
        ></v-textarea>
    </v-container>
    <div class="footer">
        <v-icon icon="mdi-eye-off" />
        <div>
            <p v-html="languages.label('LBL_KUDOS_PRIVATE')" />
            <p class="note" v-html="languages.label('LBL_KUDOS_PRIVATE_TEXT_ON_FORM')" />
        </div>
        <v-switch v-model="store.form.private" color="primary" hide-details class="switch" />
    </div>
    <div class="buttons-container">
        <MintButton
            v-if="store.form.kudosId"
            @click="deleteKudos"
            class="mr-auto ml-3"
            :loading="store.isRemovingLoading"
            :disabled="store.form.loading || !store.form.text"
            :icon="store.form.kudosId ? (store.isRemovingLoading ? '' : 'mdi-trash-can-outline') : ''"
            :text="languages.label('LBL_KUDOS_REMOVE_BUTTON')"
            variant="text-danger"
        />
        <MintButton
            @click="store.formReset"
            :disabled="store.form.loading || store.isRemovingLoading"
            :text="languages.label('LBL_KUDOS_CANCEL_BUTTON')"
            variant="text"
        />
        <MintButton
            @click="sendKudos"
            :loading="store.form.loading"
            :disabled="store.form.loading || store.isRemovingLoading || !store.form.text"
            :icon="store.form.loading ? '' : store.form.kudosId ? '' : 'mdi-send'"
            :text="
                store.form.kudosId
                    ? languages.label('LBL_KUDOS_UPDATE_BUTTON')
                    : languages.label('LBL_KUDOS_SEND_BUTTON')
            "
            variant="primary"
        />
    </div>
    <p
        v-if="store.form.error"
        class="mt-8 text-red-lighten-1 text-center"
        v-text="languages.label('LBL_KUDOS_ERROR')"
    />
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { useMintKudosStore } from './MintKudosStore'
import MintButton from '../MintButtons/MintButton.vue'

const store = useMintKudosStore()
const languages = useLanguagesStore()

async function sendKudos() {
    store.form.error = false
    store.form.loading = true
    if (!store.form.user?.id) return store.showError()
    const response = await store.addKudos(store.form.user.id, store.form.text, store.form.private, store.form.kudosId)
    if (response) {
        store.showSuccess()
    } else {
        store.showError()
    }
}
async function deleteKudos() {
    const response = await store.deleteKudos(store.form.kudosId)
    if (response) {
        store.formReset()
        store.activeView = 'kudos-list'
    } else {
        store.showError()
    }
}

const rules = [
    (v: string) => v.length <= 255 || languages.label('LBL_KUDOS_CHARACTERS_WARNING'),
    (v: string) => !!v || languages.label('LBL_KUDOS_EMPTY_MESSAGE'),
]
</script>

<style scoped lang="scss">
.header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 8px;
}
.footer {
    padding: 8px 26px;
    display: flex;
    gap: 16px;
}
.buttons-container {
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    margin: 16px 16px 0 0;
}
.photo-container {
    width: 32px;
    height: 32px;
    & i {
        font-size: 32px;
    }
}
img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 50%;
}
* {
    color: rgba(var(--v-theme-on-surface), var(--v-large-emphasis-opacity));
}
.note {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
.switch {
    margin-left: auto;
    :deep(.v-selection-control) {
        justify-content: end;
    }
    :deep(.v-input__control) {
        margin-top: -16px;
    }
}
</style>
