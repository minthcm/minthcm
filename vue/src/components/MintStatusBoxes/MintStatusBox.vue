<template>
    <div :class="['mint-status-box', `mint-status-box-${props.type}`]">
        <div class="mint-status-box-icon">
            <v-icon :icon="icons[props.type]" />
        </div>
        <div class="mint-status-box-content">
            <span class="mint-status-box-title" v-text="title" />
            <slot></slot>
        </div>
        <div v-if="props.closeable" class="mint-status-box-close">
            <v-btn icon variant="text" density="compact" @click="emit('close')">
                <v-icon icon="mdi-close" size="small" />
            </v-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { StatusBoxOptions } from '@/store/statusBoxes';

const languages = useLanguagesStore()

const props = defineProps<StatusBoxOptions>()

const emit = defineEmits(['close'])

const title = computed(() => {
    const type = props.type?.toUpperCase()
    return (languages.languages.app_strings?.[`LBL_MINT4_STATUS_BOX_${type}`] ?? type) + '! '
})

const icons = {
    error: 'mdi-alert-outline',
    success: 'mdi-check',
    info: 'mdi-information-outline',
}

onMounted(() => {
    if (props.autoClose) {
        setTimeout(() => {
            emit('close')
        }, props.autoCloseDelay)
    }
})
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
            display: block;
            font-weight: 600;
        }
    }

    &:has(.mint-status-box-close) .mint-status-box-content {
        padding-right: 12px;
    }

    .mint-status-box-close {
        align-self: stretch;
        padding: 8px 8px 8px 0;
    }
}

.mint-status-box-error {
    background: var(--mint-status-error-bg);
    color: var(--mint-status-error-text);

    .mint-status-box-content,
    .mint-status-box-close {
        background: var(--mint-status-error-bg-inv);
        color: var(--mint-status-error-text-inv);
    }
}

.mint-status-box-success {
    background: var(--mint-status-ok-bg);
    color: var(--mint-status-ok-text);

    .mint-status-box-content,
    .mint-status-box-close {
        background: var(--mint-status-ok-bg-inv);
        color: var(--mint-status-ok-text-inv);
    }
}

.mint-status-box-info {
    background: var(--mint-status-warn-bg);
    color: var(--mint-status-warn-text);

    .mint-status-box-content,
    .mint-status-box-close {
        background: var(--mint-status-warn-bg-inv);
        color: var(--mint-status-warn-text-inv);
    }
}
</style>
