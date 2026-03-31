<template>
    <div class="install-view-completed">
        <h1>Finished</h1>
        <MintStatusBox type="success">
            Your MintHCM installation is ready. Go ahead and log-in as an administrator.
        </MintStatusBox>
        <MintButton variant="primary" text="Go to login" style="width: 100%" @click="goToLogin" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, onBeforeUnmount } from 'vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBoxes/MintStatusBox.vue'
onMounted(() => {
    window.addEventListener('keydown', handleKeyDown)
})

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown)
})

function handleKeyDown(event) {
    if (event.key === 'Enter') {
        goToLogin()
    }
}

function goToLogin() {
    location.reload()
}

</script>

<style scoped lang="scss">
.install-view-completed {
    display: flex;
    flex-direction: column;
    gap: 40px;
    justify-content: center;
    text-align: center;

    h1 {
        font-size: 24px;
    }
}
</style>
