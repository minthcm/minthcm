<template>
    <v-list class="mint-menu-list" nav density="compact" color="secondary">
        <v-list-item v-for="item in props.items" :key="item.title" :to="item.url" @click="item.onClick" :active="false">
            <template v-if="item.icon" #prepend>
                <span style="font-size: 11px"><v-icon :icon="getIcon(item.icon)" /></span>
            </template>
            <v-list-item-title>
                {{ item.title }}
            </v-list-item-title>
        </v-list-item>
    </v-list>
</template>

<script setup lang="ts">
export interface MenuListItem {
    title: string
    icon?: string
    url?: string
    onClick?: () => void
}

interface Props {
    items: MenuListItem[]
}

const props = defineProps<Props>()

//TODO: global function?
function getIcon(icon: string) {
    if (!icon) {
        return ''
    }
    if (icon.slice(0, 4) === 'mdi-') {
        return icon // Material Design Icons
    }
    if (icon.slice(0, 3) === 'fi-') {
        return icon // Flag Icons
    }
    return `mdi-${icon}` // return mdi by default
}

</script>

<style lang="scss">
.mint-menu-list {
    min-width: 186px;
    padding: 2px 0px;
    color: rgb(var(--v-theme-secondary));
    .v-list-item {
        margin: 0px;
        padding: 0px 12px;
        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
            background: rgb(var(--v-theme-primary-light));
        }
    }
    .v-list-item-title {
        font-size: 14px;
        font-weight: 600;
    }
    .v-icon {
        opacity: 1;
        margin-inline-end: 8px;
    }
}
</style>
