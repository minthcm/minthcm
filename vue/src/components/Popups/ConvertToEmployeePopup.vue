<template>
    <div class="mint-popup-convert">
        <v-form ref="formRef" v-model="isFormValid">
            <p v-html="languagesStore.label('LBL_CONVERT_TO_EMPLOYEE_DESCRIPTION', props.data.module_name)"></p>
            <v-radio-group inline v-model="userType" :rules="[roleRule]">
                <v-radio
                    :label="languagesStore.label('LBL_CONVERT_TO_EMPLOYEE_CREATE_EMPLOYEE', props.data.module_name)"
                    value="employee"
                ></v-radio>
                <v-radio
                    :label="languagesStore.label('LBL_CONVERT_TO_EMPLOYEE_CREATE_USER', props.data.module_name)"
                    value="user"
                ></v-radio>
            </v-radio-group>
            <div v-if="userType === 'user'">
                <p>{{ languagesStore.label('LBL_CONVERT_TO_EMPLOYEE_UNIQUE_LOGIN', props.data.module_name) }}</p>
                <v-text-field
                    v-model="userName"
                    :label="languagesStore.label('LBL_CONVERT_TO_EMPLOYEE_USER_LOGIN', props.data.module_name)"
                    :error-messages="userNameErrors"
                ></v-text-field>
            </div>
            <div class="mint-popup-buttons">
                <v-btn @click="submitForm" color="primary" :loading="loading" :disabled="loading">
                    {{ languagesStore.label('LBL_SUBMIT_BUTTON_LABEL', props.data.module_name) }}
                </v-btn>
            </div>
        </v-form>
    </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import { usersApi } from '@/api/users.api'
import { candidaturesApi } from '@/api/candidatures.api'
import { useLanguagesStore } from '@/store/languages'
import type { VForm } from 'vuetify/components'

interface ConvertToEmployeePopupProps {
    data: {
        candidature_id: string
        module_name: string
    }
}

const props = defineProps<ConvertToEmployeePopupProps>()
const router = useRouter()
const emit = defineEmits(['close'])
const languagesStore = useLanguagesStore()
const formRef = ref<InstanceType<typeof VForm> | null>(null)
const isFormValid = ref<boolean>(false)
const userType = ref<'employee' | 'user' | ''>('')
const userName = ref<string>('')
const userNameErrors = ref<string[]>([])
const loading = ref<boolean>(false)
const roleRule = (value: any) =>
    !!value || languagesStore.label('LBL_MINT4_ERROR_REQUIRED_FIELD', props.data.module_name)

const submitForm = async () => {
    loading.value = true
    userNameErrors.value = []
    isFormValid.value = true
    if (userType.value) {
        if (!userName.value?.length) {
            userNameErrors.value = [languagesStore.label('LBL_MINT4_ERROR_REQUIRED_FIELD', props.data.module_name)]
            isFormValid.value = false
        } else {
            const isUnique = await usersApi.isLoginUnique(userName.value)
            if (!isUnique.data) {
                isFormValid.value = false
                userNameErrors.value = [
                    languagesStore.label('ERR_CONVERT_TO_EMPLOYEE_USER_LOGIN', props.data.module_name),
                ]
            }
        }
    }
    const result = await formRef.value?.validate()
    if (result?.valid && isFormValid.value) {
        const result = await candidaturesApi.convert(userType.value, props.data.candidature_id, userName.value)
        emit('close')
        router.push(`/modules/${result.data.module_name}/DetailView/${result.data.created_record_id}`)
    }
    loading.value = false
}
</script>

<style scoped lang="scss">
.mint-popup-convert {
    display: flex;
    flex-direction: column;
    gap: 32px;
    min-width: 400px;
    .mint-popup-buttons {
        display: flex;
        justify-content: space-between;
        border-top: thin solid #0002;
        padding: 16px 0px 0px 0px;
    }
}
</style>
