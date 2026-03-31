<template>
    <v-icon color="secondary" :icon="parsedBool ? 'mdi-checkbox-marked-circle' : 'mdi-close'" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()

const parsedBool = computed<boolean>(() => {
    //TODO: 'false', empty array, trim ...
    return !!Number(props.modelValue)
})
</script>
