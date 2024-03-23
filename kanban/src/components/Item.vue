<template>
  <keep-alive>
    <component
      v-if="itemComponent"
      :is="itemComponent"
      :item="item"
      :defs="defs"
      :classesFromParent="classesFromParent"
      @item-click="(item) => $emit('item-click', item)"
      @open-fullscreen="(item) => $emit('open-fullscreen', item)"
    />
  </keep-alive>
</template>

<script>
import Vue from 'vue'

export default {
  name: 'Item',
  props: {
    item: {
      type: Object,
      required: true
    },
    defs: {
      type: Object,
      defalt: {}
    },
    classesFromParent: {
      type: Object,
      defalt: {}
    }
  },
  computed: {
    itemComponent () {
      return this.loadItemComponent(this.item.module_name) || this.loadItemComponent()
    }
  },
  methods: {
    loadItemComponent (module = 'Base') {
      let componentName = `${module}-Item`
      if (!(componentName in Vue.options.components)) {
        try {
          const componentConfig = require(`@/modules/${module}/Item`)
          Vue.component(componentName, componentConfig.default || componentConfig)
        } catch (e) {
          componentName = false
        }
      }
      return componentName
    }
  }
}
</script>
