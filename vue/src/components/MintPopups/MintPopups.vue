<template>
  <transition-group 
    name="scale-transition"
    @after-enter="handleAfterEnter"
  >
    <MintPopup 
      v-for="popup in popups" 
      :key="popup" 
      :popup="popup"
      :ref="element => setPopupRef(element, popup)"
    />
  </transition-group>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { usePopupsStore } from '@/store/popups'
import MintPopup from './MintPopup.vue'
import { ref } from 'vue'

const { popups } = storeToRefs(usePopupsStore())
const popupRefs = ref<Map<any, InstanceType<typeof MintPopup>>>(new Map())

const setPopupRef = (element: any, popup: any) => {
  if (element) {
    popupRefs.value.set(popup, element)
  }
}

const handleAfterEnter = (element: Element) => {
  const focusable = element.querySelector<HTMLElement>(
    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
  )
  focusable?.focus()
}

</script>

<style scoped lang="scss"></style>
