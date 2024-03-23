<template>
    <div>
        <h2 v-text="languages.label('LBL_MINT4_AUTH_FORGET_TITLE')" />
        <MintStatusBox v-if="forgetSuccess" type="success">{{
            languages.label('LBL_MINT4_AUTH_FORGET_SUCCESS')
        }}</MintStatusBox>
        <MintStatusBox v-else-if="forgetError" type="error">{{ languages.label(forgetError) }}</MintStatusBox>
        <template v-if="!forgetSuccess">
            <v-text-field
                v-model="authViewStore.username"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_USERNAME')"
                variant="outlined"
                hide-details
                :disabled="isSubmiting"
            />
            <v-text-field
                v-model="email"
                color="primary"
                base-color="#00000099"
                density="comfortable"
                :label="languages.label('LBL_MINT4_AUTH_EMAIL')"
                variant="outlined"
                hide-details
                :disabled="isSubmiting"
            />
            <MintButton
                variant="primary"
                :text="languages.label('LBL_MINT4_AUTH_FORGET_BTN')"
                @click="handleForgetBtnClick"
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios, { AxiosError } from 'axios'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBox.vue'
import { useAuthViewStore } from './AuthViewStore'

const authViewStore = useAuthViewStore()
const languages = useLanguagesStore()

const email = ref('')
const forgetSuccess = ref(false)
const forgetError = ref('')
const isSubmiting = ref(false)

onMounted(() => {
    authViewStore.footerNavAction = {
        routeName: 'auth-login',
        label: `← ${languages.label('LBL_MINT4_AUTH_BACK_TO_LOGIN')}`,
    }
})

async function handleForgetBtnClick() {
    forgetError.value = ''
    try {
        await axios.post('api/forget_password', {
            username: authViewStore.username,
            email: email.value,
        })
        forgetSuccess.value = true
    } catch (err) {
        forgetError.value = (err as AxiosError<{ message: string }>).response?.data?.message ?? ''
    }
}
</script>
