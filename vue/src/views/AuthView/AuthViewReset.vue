<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_RESET_TITLE')" />
        <template v-if="resetSuccess">
            <MintStatusBox type="success">
                {{ languages.label('LBL_MINT4_AUTH_RESET_SUCCESS') }}
            </MintStatusBox>
            <MintButton
                variant="primary"
                :text="languages.label('LBL_MINT4_AUTH_RESET_BACK_TO_LOGIN_BTN')"
                @click="$router.push({ name: 'auth-login' })"
            />
        </template>
        <template v-if="tokenError">
            <MintStatusBox type="error">
                {{ languages.label('LBL_MINT4_AUTH_RESET_TOKEN_ERROR') }}
            </MintStatusBox>
            <MintButton
                variant="primary"
                :text="languages.label('LBL_MINT4_AUTH_RESET_AGAIN_BTN')"
                @click="$router.push({ name: 'auth-forget' })"
            />
        </template>
        <template v-else>
            <v-text-field
                v-model="username"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_USERNAME')"
                variant="outlined"
                hide-details
                disabled
            />
            <MintStatusBox type="info">
                <span v-text="languages.label('LBL_MINT4_AUTH_PASSWORD_RULES')" />
                <div class="password-rules-container">
                    <div v-if="passwordRules.minpwdlength" :class="{ 'rule-valid': passwordValidation.minpwdlength }">
                        <v-icon size="12" :icon="passwordValidation.minpwdlength ? 'mdi-check' : 'mdi-minus'" />
                        {{
                            languages
                                .label('LBL_MINT4_AUTH_PASSWORD_RULE_MINPWDLENGTH')
                                .replace('{length}', passwordRules.minpwdlength)
                        }}
                    </div>
                    <div v-if="passwordRules.onelower" :class="{ 'rule-valid': passwordValidation.onelower }">
                        <v-icon size="12" :icon="passwordValidation.onelower ? 'mdi-check' : 'mdi-minus'" />
                        {{ languages.label('LBL_MINT4_AUTH_PASSWORD_RULE_ONELOWER') }}
                    </div>
                    <div v-if="passwordRules.oneupper" :class="{ 'rule-valid': passwordValidation.oneupper }">
                        <v-icon size="12" :icon="passwordValidation.oneupper ? 'mdi-check' : 'mdi-minus'" />
                        {{ languages.label('LBL_MINT4_AUTH_PASSWORD_RULE_ONEUPPER') }}
                    </div>
                    <div v-if="passwordRules.onenumber" :class="{ 'rule-valid': passwordValidation.onenumber }">
                        <v-icon size="12" :icon="passwordValidation.onenumber ? 'mdi-check' : 'mdi-minus'" />
                        {{ languages.label('LBL_MINT4_AUTH_PASSWORD_RULE_ONENUMBER') }}
                    </div>
                    <div v-if="passwordRules.onespecial" :class="{ 'rule-valid': passwordValidation.onespecial }">
                        <v-icon size="12" :icon="passwordValidation.onespecial ? 'mdi-check' : 'mdi-minus'" />
                        {{ languages.label('LBL_MINT4_AUTH_PASSWORD_RULE_ONESPECIAL') }}
                    </div>
                </div>
            </MintStatusBox>
            <v-text-field
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_PASSWORD')"
                variant="outlined"
                hide-details
                :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="showPassword = !showPassword"
                :disabled="isSubmiting"
            />
            <v-text-field
                v-model="password2"
                :type="showPassword ? 'text' : 'password'"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_PASSWORD_REPEAT')"
                variant="outlined"
                hide-details
                :disabled="isSubmiting"
            />
            <MintButton
                variant="primary"
                :text="languages.label('LBL_MINT4_AUTH_RESET_BTN')"
                @click="submitResetPassword"
                :disabled="!isPasswordValid"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useAuthViewStore } from './AuthViewStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBox.vue'
import { usePreferencesStore } from '@/store/preferences'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

onMounted(async () => {
    store.footerNavAction = {
        routeName: 'auth-login',
        label: `← ${languages.label('LBL_MINT4_AUTH_BACK_TO_LOGIN')}`,
    }
    token.value = route.query.token?.toString() ?? null
    if (!token.value) {
        handleTokenError()
    }
    try {
        const validTokenResponse = await axios.get(`api/validation_token?token=${token.value}`)
        if (validTokenResponse.data?.username) {
            username.value = validTokenResponse.data.username
        } else {
            handleTokenError()
        }
    } catch (err) {
        handleTokenError()
    }
})

function handleTokenError() {
    tokenError.value = true
    store.footerNavAction = null
}

const languages = useLanguagesStore()
const preferences = usePreferencesStore()
const store = useAuthViewStore()
const password = ref('')
const password2 = ref('')
const showPassword = ref(false)
const resetSuccess = ref(false)
const tokenError = ref(false)

const token = ref<string | null>('')
const username = ref('')
const isSubmiting = ref(false)

const passwordRules = computed(() => preferences.global?.password_rules ?? {})

const passwordValidation = computed(() => ({
    minpwdlength: !passwordRules.value.minpwdlength || password.value?.length >= passwordRules.value.minpwdlength,
    onelower: !passwordRules.value.onelower || password.value.match(/[a-z]/),
    oneupper: !passwordRules.value.oneupper || password.value.match(/[A-Z]/),
    onenumber: !passwordRules.value.onenumber || password.value.match(/[0-9]/),
    onespecial: !passwordRules.value.onespecial || password.value.match(/[^A-Za-z0-9]/), //todo: sync with php
}))

const isPasswordValid = computed(
    () =>
        password.value === password2.value && Object.values(passwordValidation.value).every((ruleValid) => !!ruleValid),
)

async function submitResetPassword() {
    if (!isPasswordValid.value) {
        return
    }
    const response = await axios.post('api/reset_forget_password', {
        username: username.value,
        new_password: password.value,
        token: token.value,
    })
    if (response.status === 200) {
        resetSuccess.value = true
        store.footerNavAction = null
    }
}
</script>

<style scoped lang="scss">
.password-rules-container {
    display: flex;
    flex-direction: column;
    gap: 2px;

    .rule-valid {
        color: green;
        font-weight: 600;
    }
}
</style>
