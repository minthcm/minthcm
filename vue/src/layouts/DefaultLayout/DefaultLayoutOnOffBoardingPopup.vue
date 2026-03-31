<template>
    <div class="modules-popup">
    </div>
</template>

<style scoped lang="scss">
.modules-popup {
    display: flex;
    flex-wrap: wrap;
    width: 80vw;
    height: 80vw;
    max-width: 1300px;
    gap: 16px;
    padding: 16px 24px;
    .module-tile {
        display: flex;
        flex-direction: column;
        gap: 14px;
        transition: all 150ms ease-in-out;
        border-radius: 8px;
        width: 96px;
        height: 96px;
        padding: 8px 8px;
        align-items: center;
        color: rgb(var(--v-theme-secondary));
        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
            background: #e0ece9;
        }

        span {
            font-size: 14px;
            letter-spacing: 0.13px;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 88px;
        }
    }
}
</style>
