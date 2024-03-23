<template>
  <div @click.stop="$emit('open-fullscreen')">
    <img
      src="include/KanbanView/fullscreen.png"
      class="icon-fullscreen"
    />
  </div>
</template>

<style scoped>
.icon-fullscreen {
  float: right;
}
</style>
