<template>
  <div>
    <VSkeletonLoader
      v-for="n in count"
      :key="n"
      class="my-4"
      type="image"
      height="36px"
    />
  </div>
</template>

<script>
import { VSkeletonLoader } from 'vuetify/lib'
export default {
  name: 'Skeleton',
  components: {
    VSkeletonLoader
  },
  props: {
    count: {
      type: Number,
      default: 1
    }
  }
}
</script>
