<template>
    <v-progress-circular v-if="store.inlineEditFieldSaving === props.defs?.name" size="16" indeterminate />
    <v-icon
        v-else-if="!props.defs?.readonly"
        icon="mdi-pencil"
        size="small"
        class="detail-view-edit-icon"
        @click="editBtnClicked"
    />
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
const store = useRecordViewStore()
interface Props {
    defs?: FieldVardef
}
const props = defineProps<Props>()
const emit = defineEmits(['inlineEditBtnClicked'])

function editBtnClicked() {
    if (props?.defs?.name && typeof props.defs.name === 'string' && props.defs.name.length > 0) {
        emit('inlineEditBtnClicked', props.defs.name)
    }
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
