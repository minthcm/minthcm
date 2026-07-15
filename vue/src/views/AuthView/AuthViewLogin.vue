<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_LOGIN_TITLE')" />
        <MintStatusBox v-if="loginError" type="error">{{
            languages.label('LBL_MINT4_AUTH_LOGIN_ERROR')
        }}</MintStatusBox>
        <MintStatusBox v-if="route.query.reset === 'success'" type="success">
            {{ languages.label('LBL_MINT4_AUTH_RESET_SUCCESS') }}
        </MintStatusBox>
        <template v-if="backend.ssoRedirectUrl">
            <MintButton
                variant="primary"
                :text="languages.label('LBL_MINT4_AUTH_SSO_LOGIN_BTN')"
                @click="handleSsoLogin"
            />
            <div class="login-divider" v-text="languages.label('LBL_MINT4_AUTH_OR_LOCAL_LOGIN')" />
        </template>
        <v-form class="login-form" autocomplete="on" @submit.prevent="handleSubmit">
            <v-text-field
                class="login-input"
                v-model.trim="authViewStore.username"
                color="primary"
                name="username"
                autocomplete="username"
                autofocus
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_USERNAME')"
                variant="outlined"
                hide-details
                :disabled="isSubmiting"
            />
            <v-text-field
                class="login-input"
                v-model.trim="password"
                :type="showPassword ? 'text' : 'password'"
                name="password"
                autocomplete="current-password"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_PASSWORD')"
                variant="outlined"
                hide-details
                :disabled="isSubmiting"
                >
                <template #append-inner>
                <v-btn
                    icon 
                    variant="text"
                    density="compact"
                    class="login-password-toggle"
                    :aria-label="showPassword ? languages.label('LBL_MINT4_AUTH_HIDE_PASSWORD') : languages.label('LBL_MINT4_AUTH_SHOW_PASSWORD')"
                    @click="showPassword = !showPassword"
                >
                    <v-icon size="20">
                    {{ showPassword ? 'mdi-eye-off' : 'mdi-eye' }}
                    </v-icon>
                </v-btn>
                </template>
            </v-text-field>
            <MintButton
                :variant="backend.ssoRedirectUrl ? 'regular' : 'primary'"
                :text="languages.label('LBL_MINT4_AUTH_LOGIN_BTN')"
                @click="handleSubmit"
            />
        </v-form>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthViewStore } from './AuthViewStore'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import { usePreferencesStore } from '@/store/preferences'
import { useRoute, useRouter } from 'vue-router'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBoxes/MintStatusBox.vue'

const authViewStore = useAuthViewStore()
const backend = useBackendStore()
const languages = useLanguagesStore()
const auth = useAuthStore()
const preferences = usePreferencesStore()

const checkCurrentLanguageExists = () => {
    const existingLanguages = preferences.global?.languages || {}
    if (Object.keys(existingLanguages).length > 0) {
        let currentLanguageExists = false
        Object.keys(existingLanguages).forEach((key) => {
            if (key === languages.currentLanguage) {
                currentLanguageExists = true
            }
        })
        if (!currentLanguageExists) {
            languages.currentLanguage = 'en_us'
            localStorage.setItem('currentLang', languages.currentLanguage)
            document.location.reload()
        }
    }
}

onMounted(() => {
    const showForgetLink = !preferences.global?.ldap_enabled
    if (showForgetLink) {
        authViewStore.footerNavAction = {
            routeName: 'auth-forget',
            label: languages.label('LBL_MINT4_AUTH_FORGET_PASSWORD_QUESTION'),
        }
    }
    checkCurrentLanguageExists()
})

const password = ref('')
const showPassword = ref(false)
const isSubmiting = ref(false)
const loginError = ref(false)
const router = useRouter()
const route = useRoute()

function handleSsoLogin() {
    if (backend.ssoRedirectUrl) {
        location.href = backend.ssoRedirectUrl
    }
}

async function handleSubmit() {
    loginError.value = false
    if (isSubmiting.value) {
        return
    }
    isSubmiting.value = true
    const result = await auth.authenticate(authViewStore.username, password.value)
    if (result === true) {
        backend.initialLoading = true
        const redirect = router.currentRoute.value?.query?.redirect

        if (redirect && typeof redirect === 'string') {
            await backend.init()
            await router.replace({ path: redirect })
        } else {
            router.go(0)
        }
    } else if (result === false) {
        loginError.value = true
    }
    isSubmiting.value = false
}
</script>

<style scoped lang="scss">
.login-form {
    display: flex;
    flex-direction: column;
    gap: 32px;
    width: 100%;
}
.login-divider {
    text-align: center;
    color: #00000099;
    font-size: 14px;
    margin: 16px 0;
}
</style>
<style>
/* Prevent autofill background color flash */
.login-form .login-input input:-webkit-autofill,
.login-form .login-input input:-webkit-autofill:hover,
.login-form .login-input input:-webkit-autofill:focus,
.login-form .login-input input:-webkit-autofill:active {
    transition: background-color 9999s ease-in-out 0s;
}
.login-password-toggle {
    min-width: 0;
    padding: 0;
}

/*
 * Pure CSS autofill label fix for Vuetify outlined variant.
 * When browser applies :-webkit-autofill, force the floating label visible
 * and hide the inline label without touching JS Virtual DOM, safely bypassing
 * browser security restrictions related to password autofill scripts.
 */
.login-form .login-input .v-field:has(input:-webkit-autofill) .v-label.v-field-label {
    visibility: hidden !important;
}
.login-form .login-input .v-field:has(input:-webkit-autofill) .v-label.v-field-label--floating {
    visibility: visible !important;
    opacity: 1;
}
.login-form .login-input .v-field:has(input:-webkit-autofill) .v-field__outline__notch::before {
    opacity: 0 !important;
}
</style>
