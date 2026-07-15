<template>
    <div class="mcp-login-view">
        <div v-if="isLoading" class="mcp-login-loader">
            <v-progress-circular indeterminate color="primary" size="40" />
        </div>

        <div v-else class="mcp-login-container">
            <img :src="logoSrc" @error="handleLogoError" height="32" alt="" aria-hidden="true" />

            <MintStatusBox v-if="flowError" type="error">
                {{ languages.label('LBL_MINT4_MCP_SESSION_EXPIRED') }}
            </MintStatusBox>

            <template v-else>
                <div class="client-info" v-if="mcpStore.clientName">
                    <p class="client-title">
                        <strong>{{ mcpStore.clientName }}</strong>
                        {{ languages.label('LBL_MINT4_MCP_CLIENT_REQUESTING') }}
                    </p>
                    <div class="scope-list" v-if="parsedScopes.length">
                        <p class="scope-heading">{{ languages.label('LBL_MINT4_MCP_PERMISSIONS_HEADER') }}</p>
                        <div v-for="s in parsedScopes" :key="s.key" class="scope-item">
                            <v-icon size="16" color="primary">mdi-check</v-icon>
                            {{ s.label }}
                        </div>
                    </div>
                </div>

                <div class="form-content">
                    <MintStatusBox v-if="loginError" type="error">
                        {{ languages.label('LBL_MINT4_AUTH_LOGIN_ERROR') }}
                    </MintStatusBox>
                    <MintStatusBox v-if="authorizeError" type="error">
                        {{ languages.label('LBL_MINT4_MCP_AUTHORIZE_FAILED') }}
                    </MintStatusBox>

                    <div v-if="isRedirecting" class="redirecting">
                        <v-progress-circular indeterminate color="primary" size="24" />
                        <span>{{ languages.label('LBL_MINT4_MCP_REDIRECTING') }}</span>
                    </div>

                    <template v-else-if="isAuthenticated">
                        <p class="signed-in-as">
                            {{ languages.label('LBL_MINT4_MCP_SIGNED_IN_AS') }}
                            <strong>{{ mcpStore.authenticatedUser }}</strong>
                        </p>
                        <div class="decision-buttons">
                            <MintButton
                                variant="secondary"
                                :text="languages.label('LBL_MINT4_MCP_CANCEL_BTN')"
                                @click="handleDeny"
                                :disabled="isSubmitting"
                            />
                            <MintButton
                                variant="primary"
                                :text="languages.label('LBL_MINT4_MCP_AUTHORIZE_BTN')"
                                @click="handleAuthorize"
                                :disabled="isSubmitting"
                            />
                        </div>
                    </template>

                    <template v-else>
                        <h1 v-text="languages.label('LBL_MINT4_AUTH_LOGIN_TITLE')" />
                        <v-form class="login-form" @submit.prevent="handleLoginSubmit" :aria-label="languages.label('LBL_MINT4_AUTH_LOGIN_TITLE')">
                            <v-text-field
                                class="login-input"
                                v-model.trim="username"
                                color="primary"
                                name="username"
                                base-color="#00000099"
                                density="comfortable"
                                :label="languages.label('LBL_MINT4_AUTH_USERNAME')"
                                variant="outlined"
                                hide-details
                                :disabled="isSubmitting"
                            />
                            <v-text-field
                                class="login-input"
                                v-model.trim="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                color="primary"
                                base-color="#00000099"
                                density="comfortable"
                                :label="languages.label('LBL_MINT4_AUTH_PASSWORD')"
                                variant="outlined"
                                hide-details
                                :disabled="isSubmitting"
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
                                        <v-icon size="20">{{ showPassword ? 'mdi-eye-off' : 'mdi-eye' }}</v-icon>
                                    </v-btn>
                                </template>
                            </v-text-field>
                            <div class="decision-buttons">
                                <MintButton
                                    variant="secondary"
                                    :text="languages.label('LBL_MINT4_MCP_CANCEL_BTN')"
                                    @click="handleDeny"
                                    :disabled="isSubmitting"
                                />
                                <MintButton
                                    variant="primary"
                                    :text="languages.label('LBL_MINT4_AUTH_LOGIN_BTN')"
                                    @click="handleLoginSubmit"
                                    :disabled="isSubmitting"
                                />
                            </div>
                        </v-form>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { useMcpStore } from '@/store/mcp'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBoxes/MintStatusBox.vue'
import mintLogo from '@/assets/mint_logo.png'

const auth = useAuthStore()
const mcpStore = useMcpStore()
const languages = useLanguagesStore()
const route = useRoute()

const username = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(true)
const isSubmitting = ref(false)
const isRedirecting = ref(false)
const loginError = ref(false)
const authorizeError = ref(false)
const flowError = ref(false)
const logoSrc = ref('legacy/custom/themes/default/images/company_logo.png')

const scopeLabelKeys: Record<string, string> = {
    'mcp:read': 'LBL_MINT4_MCP_SCOPE_MCP_READ',
    'mcp:write': 'LBL_MINT4_MCP_SCOPE_MCP_WRITE',
    'openid': 'LBL_MINT4_MCP_SCOPE_OPENID',
    'profile': 'LBL_MINT4_MCP_SCOPE_PROFILE',
    'email': 'LBL_MINT4_MCP_SCOPE_EMAIL',
}

const isAuthenticated = computed(() => Boolean(mcpStore.authenticatedUser))

const parsedScopes = computed(() =>
    mcpStore.scope.split(' ').filter(Boolean).map(s => ({
        key: s,
        label: scopeLabelKeys[s] ? languages.label(scopeLabelKeys[s]) : s,
    }))
)

function handleLogoError() {
    logoSrc.value = mintLogo
}

async function handleAuthorize() {
    if (isSubmitting.value) return
    isSubmitting.value = true
    authorizeError.value = false
    isRedirecting.value = true
    const ok = await mcpStore.authorize()
    if (!ok) {
        authorizeError.value = true
        isRedirecting.value = false
    }
    isSubmitting.value = false
}

async function handleDeny() {
    if (isSubmitting.value) return
    isSubmitting.value = true
    isRedirecting.value = true
    const ok = await mcpStore.deny()
    if (!ok) {
        // Backend couldn't produce a valid deny redirect (invalid/expired flow).
        // Surface as a flow error — user can safely close the tab.
        flowError.value = true
        isRedirecting.value = false
    }
    isSubmitting.value = false
}

async function handleLoginSubmit() {
    loginError.value = false
    authorizeError.value = false
    if (isSubmitting.value) return
    isSubmitting.value = true

    const result = await auth.authenticate(username.value, password.value)
    password.value = ''
    if (result === false) {
        loginError.value = true
        isSubmitting.value = false
        return
    }
    if (result === undefined) {
        // auth store fired a location redirect (e.g. SAML) — leave the page as-is.
        return
    }
    // Login succeeded. auth.authenticate() doesn't populate auth.user (that's
    // backend.init()'s job, which we deliberately skip for the MCP route).
    // Re-fetch oauth-info so the backend tells us who is signed in, which
    // flips the view over to the consent screen.
    const ok = await mcpStore.fetchOauthInfo()
    if (!ok || !mcpStore.authenticatedUser) {
        authorizeError.value = true
    }
    isSubmitting.value = false
}

onMounted(async () => {
    try {
        const flowId = typeof route.query.flow === 'string' ? route.query.flow : ''
        if (!flowId) {
            flowError.value = true
            return
        }
        mcpStore.setFlowId(flowId)

        const loadLanguages = async () => {
            if (Object.keys(languages.languages.app_strings).length) return
            try {
                const loginData = (await mintApi.get('login', {
                    params: { lang: localStorage.getItem('currentLang') ?? 'en_us' },
                    rawError: true,
                })).data
                languages.languages = {
                    app_strings: loginData.languages?.app_strings ?? {},
                    app_list_strings: loginData.languages?.app_list_strings ?? {},
                    modules: { Users: loginData.languages?.Users ?? {} },
                }
            } catch {
                // Language labels fall back to raw keys — UI stays usable.
            }
        }

        const [, infoOk] = await Promise.all([loadLanguages(), mcpStore.fetchOauthInfo()])
        if (!infoOk) {
            flowError.value = true
        }
    } finally {
        isLoading.value = false
    }
})
</script>

<style scoped lang="scss">
.mcp-login-view {
    position: fixed;
    width: 100vw;
    height: 100vh;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: auto;
}

.mcp-login-loader {
    display: flex;
    align-items: center;
    justify-content: center;
}

.mcp-login-container {
    background: rgb(var(--v-theme-surface));
    border-radius: 16px;
    box-shadow: 0px 1px 12px #00997619;
    width: 100%;
    max-width: 457px;
    margin: 0 auto;
    padding: 48px 64px 40px 64px;
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: center;
}

.client-info {
    width: 100%;
    background: rgb(var(--v-theme-background));
    border-radius: 8px;
    padding: 16px;
    text-align: left;

    .client-title {
        margin: 0 0 12px 0;
        font-size: 16px;
    }

    .scope-heading {
        margin: 6px 0 6px 0;
        font-size: 16px;
        font-weight: 500;
    }

    .scope-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .scope-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: rgb(var(--v-theme-secondary));
    }
}

.form-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 24px;
    text-align: center;

    h1 {
        margin: 0;
        font-size: 24px;
    }
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
    width: 100%;
}

.signed-in-as {
    margin: 0;
    font-size: 14px;
    color: rgb(var(--v-theme-secondary));
}

.decision-buttons {
    display: flex;
    gap: 12px;
    width: 100%;

    > * {
        flex: 1;
    }
}

.redirecting {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 0;
    color: rgb(var(--v-theme-secondary));
}

:deep(.login-password-toggle) {
    min-width: 0;
    padding: 0;
}
</style>

<style>
/* Unscoped — webkit-autofill pseudo-elements can't be targeted from scoped styles. */
.mcp-login-view .login-input input:-webkit-autofill,
.mcp-login-view .login-input input:-webkit-autofill:hover,
.mcp-login-view .login-input input:-webkit-autofill:focus,
.mcp-login-view .login-input input:-webkit-autofill:active {
    transition: background-color 9999s ease-in-out 0s;
}
</style>
