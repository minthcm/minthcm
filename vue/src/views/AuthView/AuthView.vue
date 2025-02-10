<template>
    <div class="auth-view">
        <div class="auth-view-container">
            <img src="../../assets/mint_logo.png" height="32" />
            <router-view v-slot="{ Component }" class="form-content">
                <v-slide-x-transition hide-on-leave>
                    <component :is="Component" />
                </v-slide-x-transition>
            </router-view>

            <div class="auth-footer">
                <v-slide-x-transition hide-on-leave>
                    <div
                        v-if="store.footerNavAction"
                        @click="$router.push({ name: store.footerNavAction.routeName })"
                        v-text="store.footerNavAction.label"
                    />
                </v-slide-x-transition>
                <v-menu offset="16" v-if="languagesList.length > 1">
                    <template v-slot:activator="{ props, isActive }">
                        <MintButton
                            class="ms-auto"
                            v-bind="props"
                            variant="nav"
                            icon="mdi-translate"
                            :active="isActive"
                            :tooltip="languages.label('LBL_MINT4_AUTH_LANG_TOOLTIP')"
                        />
                    </template>
                    <MintMenuList :items="languagesList" />
                </v-menu>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthViewStore } from './AuthViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import axios from 'axios'
import { usePreferencesStore } from '@/store/preferences'
import { onMounted } from 'vue'

const languages = useLanguagesStore()
const backend = useBackendStore()
const store = useAuthViewStore()
const preferences = usePreferencesStore()

const languagesList = computed<MenuListItem[]>(() => {
    const getFlagCode = (code: string) => {
        let [lang, country] = code.split('_')
        if (['ar','fa','he','ur','yi'].includes(lang.toLowerCase())){
            country = 'arab'
        }
        return `fi-${country.toLowerCase()}`
    }
    return Object.entries(preferences.global?.languages ?? {}).map(([code, title]) => ({
        title: title?.toString() || '',
        icon: getFlagCode(code),
        onClick: () => {
            changeLanguage(code)
        },
    }))
})

onMounted(() => {
    const currentLang = localStorage.getItem('currentLang')
    if (!currentLang) {
        let browserLang = navigator.language
        const [languageCode, countryCode] = browserLang.split('-')
        let formattedbrowserLang = countryCode ? `${languageCode}_${countryCode}` : `${languageCode}_${languageCode.toUpperCase()}`

        const availableLanguages = Object.keys(preferences.global?.languages ?? {})

        let defaultLang = availableLanguages.find(lang => lang.toLowerCase() === formattedbrowserLang.toLowerCase())
        if (!defaultLang) {
            defaultLang = availableLanguages.find(lang => lang.toLowerCase().startsWith(languageCode.toLowerCase()))
        }

        if (!defaultLang) {
            defaultLang = "en_US"
        }
        changeLanguage(defaultLang)
    }
})

async function changeLanguage(lang = 'en_us') {
    localStorage.setItem('currentLang', lang)
    document.location.reload()
}
</script>

<style scoped lang="scss">
.auth-view {
    position: fixed;
    width: 100vw;
    height: 100vh;
    top: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: auto;
}

.auth-view-container {
    background: rgb(var(--v-theme-surface));
    border-radius: 16px;
    box-shadow: 0px 1px 12px #00997619;
    width: 100%;
    max-width: 457px;
    margin: 0px auto;
    padding: 64px 64px 32px 64px;
    display: flex;
    flex-direction: column;
    gap: 32px;
    align-items: center;
    justify-content: center;

    .form-content {
        width: 100%;
        margin-top: 32px;
        display: flex;
        text-align: center;
        flex-direction: column;
        justify-content: center;
        gap: 32px;
    }

    .auth-footer {
        width: 100%;
        margin-top: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-size: 12px;
        color: rgb(var(--v-theme-secondary));

        div {
            cursor: pointer;
            user-select: none;
            &:hover {
                color: rgb(var(--v-theme-secondary-dark));
            }
        }
    }
}
</style>

<style></style>
