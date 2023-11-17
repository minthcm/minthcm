<template>
    <div :class="['mint-status-box', `mint-status-box-${props.type}`]">
        <div class="mint-status-box-icon">
            <v-icon :icon="icons[props.type]" />
        </div>
        <div class="mint-status-box-content">
            <span class="mint-status-box-title" v-text="title" />
            <slot></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'

const languages = useLanguagesStore()

interface Props {
    type: 'error' | 'success' | 'info'
}

const props = defineProps<Props>()

const title = computed(() => {
    const type = props.type?.toUpperCase()
    return (languages.languages.app_strings?.[`LBL_MINT4_STATUS_BOX_${type}`] ?? type) + '! '
})
const icons = {
    error: 'mdi-alert-outline',
    success: 'mdi-check',
    info: 'mdi-information-outline',
}
</script>

<style scoped lang="scss">
.mint-status-box {
    display: flex;
    border-radius: 4px;
    align-items: center;
    .mint-status-box-icon {
        font-size: 14px;
        padding: 4px;
        color: white;
    }
    .mint-status-box-content {
        padding: 8px 16px;
        text-align: left;
        font-size: 14px;
        flex-grow: 1;
        .mint-status-box-title {
            font-weight: 600;
        }
    }
}

.mint-status-box-error {
    background: #ac0221;
    color: #fcf5f6;
    .mint-status-box-content {
        background: #fcf5f6;
        color: #ac0221;
    }
}

.mint-status-box-success {
    background: #097c31;
    color: #f5faf7;
    .mint-status-box-content {
        background: #f5faf7;
        color: #006222;
    }
}

.mint-status-box-info {
    background: #b0a900;
    color: #fcfcf5;
    .mint-status-box-content {
        background: #fcfcf5;
        color: #5b5800;
    }
}
</style>
