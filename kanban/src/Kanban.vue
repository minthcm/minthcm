<template>
  <VApp>
    <Table
      :defs="defs"
      :items="items"
      @item-change="(itemID, oldOrder, newOrder, oldStatus, newStatus) => $emit('item-change', itemID, oldOrder, newOrder, oldStatus, newStatus)"
      @item-add="(key) => $emit('item-add', key)"
      @item-click="(itemID) => $emit('item-click', itemID)"
      @open-fullscreen="(itemID) => $emit('open-fullscreen', itemID)"
    />
  </VApp>
</template>

<script>
import { VApp } from 'vuetify/lib'
import vuetify from './plugins/vuetify'
import Table from './components/Table'
export default {
  name: 'Kanban',
  vuetify,
  components: {
    VApp,
    Table
  },
  data () {
    return {
      defs: {},
      items: {}
    }
  }
}
</script>

<style>
@import "../node_modules/vuetify/dist/vuetify.min.css";
@import url("https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css");
.v-application {
  background: inherit !important;
}
</style>
