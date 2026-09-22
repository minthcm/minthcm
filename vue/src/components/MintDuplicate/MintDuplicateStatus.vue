<template>
  <div class="duplicate-banner">
    <span class="duplicate-text">{{ text }}</span>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'

const props = defineProps({
  count: {
    type: Number,
    required: true
  },
  module: {
    type: String,
    required: true
  }
})

const languages = useLanguagesStore()
const labelText = languages.label('LBL_DUPLICATE_DETECTED');
const text = computed(() => {
  return labelText.replace('$count', props.count).replace('$module', props.module);
})
</script>

<style scoped>
.duplicate-banner {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgb(var(--v-theme-duplicate-status-background));
  padding: 10px 0;
  box-sizing: border-box;
  height: 50px;
}

.duplicate-text {
  font-size: 16px;
  color: rgb(var(--v-theme-duplicate-status-font));
}
</style>
