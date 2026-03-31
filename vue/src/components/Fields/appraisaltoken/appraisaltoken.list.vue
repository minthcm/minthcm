<template>
    <MintButton
        v-if="props.data.bean[props.defs.name]"
        :text="languages.label('LBL_OPEN_FORM', 'Appraisals')"
        @click="openForm"
    />
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const openForm = () => {
    window.open(
        `legacy/index.php?entryPoint=ShowAppraisal&appraisal_id=${props.data.bean.id}&token=${
            props.data.bean[props.defs.name]
        }`,
        '_blank',
    )
}
</script>

<style scoped lang="scss"></style>
