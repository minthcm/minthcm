<template>
  <VCard
    v-ripple="item.editable || item.detailview"
    :class="{
      'pa-2': true,
      'mb-2': true,
      'inactive': !item.editable && !item.detailview,
      pointer: item.editable || item.detailview,
      default: !item.editable && !item.detailview,
      ...classesFromParent,
    }"
    @click="$emit('item-click', item)"
  >
    <FullscreenButton @open-fullscreen="() => $emit('open-fullscreen', item)" />
    {{ item.name }}
  </VCard>
</template>

<script>
import { VCard } from "vuetify/lib";
import FullscreenButton from '../../components/FullscreenButton.vue'

export default {
  name: "Base-Item",
  props: {
    item: Object,
    classesFromParent: Object,
  },
  components: {
    VCard,
    FullscreenButton,
  },
};
</script>
