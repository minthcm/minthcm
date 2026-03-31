<template>
    <transition-group name="scale-transition" class="mint-status-boxes" tag="div">
        <MintStatusBox
            v-for="statusBox in statusBoxes"
            :key="statusBox[0]"
            v-bind="statusBox[1]"
            @close="useStatusBoxesStore().close(statusBox[0])"
        >
            {{ statusBox[1].message }}
        </MintStatusBox>
    </transition-group>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import MintStatusBox from './MintStatusBox.vue'
import { useStatusBoxesStore } from '@/store/statusBoxes'

const { statusBoxes } = storeToRefs(useStatusBoxesStore())
</script>

<style scoped lang="scss">
.mint-status-boxes {
    position: fixed;
    top: 16px;
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    z-index: 2001;
    display: flex;
    flex-direction: column;
    gap: 16px;

    .mint-status-box {
        box-shadow: 0px 3px 6px #00000029;
    }
}
</style>
