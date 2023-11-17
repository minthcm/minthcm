<template>
    <v-icon
        v-if="!props.defs?.readonly"
        icon="mdi-pencil"
        size="small"
        class="detail-view-edit-icon"
        @click="editBtnClicked"
    />
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'
interface Props {
    defs?: FieldVardef
}
const props = defineProps<Props>()

function editBtnClicked() {
    console.log('editBtnClicked')
}
</script>

<style scoped lang="scss">
.detail-field-container .detail-view-edit-icon {
    visibility: hidden;
    padding-left: 16px;
}
.detail-field-container:hover .detail-view-edit-icon {
    visibility: visible;
}
</style>
