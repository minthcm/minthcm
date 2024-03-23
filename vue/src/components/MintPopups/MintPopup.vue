<template>
    <div ref="mintPopup" class="mint-popup">
        <div ref="mintHeader" class="mint-popup-header">
            <v-icon v-if="props.popup.icon" :icon="props.popup.icon" />
            <span v-text="props.popup.title" />
            <v-btn
                v-if="!props.popup.unclosable"
                icon="mdi-close"
                variant="text"
                density="compact"
                color="secondary"
                class="ms-auto"
                @mousedown.stop="null"
                @click="closePopup(props.popup)"
            />
        </div>
        <div class="mint-popup-content">
            <component :is="props.popup.component" @close="closePopup(props.popup)" :data="popup.data" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, onMounted, ref } from 'vue'
import { Popup, usePopupsStore } from '@/store/popups'
import { useDraggable } from '@/composables/useDraggable'
import { nextTick } from 'vue'

interface Props {
    popup: Popup
}

const props = defineProps<Props>()
const { closePopup } = usePopupsStore()
const mintPopup = ref<HTMLElement | null>(null)
const mintHeader = ref<HTMLElement | null>(null)

onMounted(async () => {
    if (mintHeader.value && mintPopup.value) {
        const draggable = useDraggable(mintHeader.value, mintPopup.value)
        draggable.init()
    }
    await nextTick()
    center()
})

function center() {
    if (mintPopup.value) {
        mintPopup.value.style.left = (window.innerWidth - mintPopup.value.clientWidth) / 2 + 'px'
        mintPopup.value.style.top = (window.innerHeight - mintPopup.value.clientHeight) / 2 + 'px'
    }
}
</script>

<style scoped lang="scss">
.mint-popup {
    min-width: 200px;
    min-height: 50px;
    box-shadow: 0px 3px 12px #00000029;
    color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
    background: rgb(var(--v-theme-surface));
    position: fixed;
    border-radius: 16px;
    z-index: 10000;

    .mint-popup-header {
        display: flex;
        align-items: center;
        font-weight: 600;
        gap: 8px;
        padding: 16px;
        border-bottom: thin solid #0099760a;
        user-select: none;
        cursor: move;
    }

    .mint-popup-content {
        max-height: calc(100vh - 100px);
        padding: 16px;
        overflow: auto;
    }
}
</style>
