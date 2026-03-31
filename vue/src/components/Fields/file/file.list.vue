<template>
  <template v-if="fileUrl">
    <a :href="fileUrl">
      {{ props.modelValue }}
    </a>
  </template>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()

const serverFileName = computed(() => {
  if (!props.data?.bean?.attributes?.id) {
    return ''
  }
  let serverFileName = props.data.bean.attributes.id
  if (isImage.value) {
    serverFileName += `_${props.defs.name}`
  }
  return serverFileName
})

const fileUrl = computed(() => {
  if (props.modelValue) {
    return `legacy/index.php?entryPoint=download&type=${props.data.bean.module}&id=${serverFileName.value}&time=${new Date().toISOString()}`
  }
  return ''
})

const isImage = computed(() => {
  return props.defs.type === 'image'
})
</script>

<style scoped lang="scss">
a {
  color: rgba(var(--v-theme-secondary), var(--v-high-emphasis-opacity));
  text-decoration: none;

  &:hover {
    text-decoration: underline;
  }
}
</style>