<template>
  <VContainer fluid>
    <VRow no-gutters>
      <VCol
        v-for="(name, key) in defs.columns"
        :key="key"
        :class="{
          'column-name': true,
          'px-2': true,
          'pt-1': true,
          'd-flex': true,
          'justify-center': true,
          'align-center': true,
          inactive: !canUserDropItemIntoColumn(key)
        }"
      >
        <p class="ma-0 font-weight-bold text-center">{{ name }}</p>
      </VCol>
    </VRow>
    <VRow no-gutters>
      <VCol
        v-for="(name, key) in defs.columns"
        :key="key"
        :class="{
          'pa-2': true,
          'pt-0': true,
          inactive: !canUserDropItemIntoColumn(key)
        }"
      >
        <Skeleton v-if="loading" :count="Math.floor(Math.random() * 5) + 1" />
        <draggable
          v-model="items[key]"
          v-bind="dragOptions"
          :group="{
            name: 'columns',
            put: to => canUserDropItemIntoColumn(to.el.dataset.column)
          }"
          @start="onDragStart"
          @end="onDragEnd"
        >
          <transition-group
            class="d-block pt-4"
            type="transition"
            :data-column="key"
          >
            <Item
              v-for="item in items[key]"
              :key="item.id"
              :item="item"
              :defs="defs"
              :classesFromParent="{
                item: canUserDragItemFromColumn(key)
              }"
              @item-click="item => $emit('item-click', item)"
              @open-fullscreen="item => $emit('open-fullscreen', item)"
            />
          </transition-group>
        </draggable>
        <VCard
          v-if="!loading && canUserCreateItem()"
          :class="{
            'pa-1': true,
            'text-center': true,
            item: !drag || canUserDragItemFromColumn(key)
          }"
          @click="$emit('item-add', key)"
        >
          <VIcon>mdi-plus-circle</VIcon>
        </VCard>
      </VCol>
    </VRow>
  </VContainer>
</template>

<script>
import { VCard, VIcon } from 'vuetify/lib'
import { VContainer, VRow, VCol } from 'vuetify/lib/components/VGrid'
import draggable from 'vuedraggable'
import Skeleton from './Skeleton'
import Item from './Item'
export default {
  name: 'Table',
  components: {
    VContainer,
    VRow,
    VCol,
    VCard,
    VIcon,
    draggable,
    Skeleton,
    Item
  },
  props: {
    defs: {
      type: Object,
      defalt: { columns: {} }
    },
    items: {
      type: Object,
      defalt: {}
    }
  },
  data () {
    return {
      drag: false,
      dropColumns: null,
      dragOptions: {
        animation: 200,
        ghostClass: 'ghost',
        draggable: '.item'
      }
    }
  },
  computed: {
    loading () {
      return !Object.keys(this.items).length
    }
  },
  methods: {
    onDragStart (evt) {
      this.drag = true
      if (this.defs.actions) {
        const from = evt.from.dataset.column
        if (this.defs.actions.length !== 0) {
          this.dropColumns = [from, ...this.defs.actions[from]]
        }
      }
    },
    onDragEnd (evt) {
      this.drag = false
      this.dropColumns = null
      this.$emit(
        'item-change',
        evt.item._underlying_vm_.id,
        evt.oldIndex + 1,
        evt.newIndex + 1,
        evt.from.dataset.column,
        evt.to.dataset.column
      )
    },
    canUserDragItemFromColumn (columnName) {
      let result = false
      if (this.defs.actions) {
        if (!this.drag) {
          if (
            this.defs.actions.length === 0 ||
            Object.keys(this.defs.actions).indexOf(columnName) !== -1
          ) {
            result = true
          }
        } else {
          result = this.canUserDropItemIntoColumn(columnName)
        }
      }
      return result
    },
    canUserDropItemIntoColumn (columnName) {
      return !this.dropColumns || this.dropColumns.indexOf(columnName) !== -1
    },
    canUserCreateItem () {
      let result = false
      if (this.defs.allow_create) {
        result = true
      }
      return result
    }
  }
}
</script>

<style scoped>
.col.column-name p {
  font-size: 14px;
}
.col.column-name.inactive {
  border-radius: 4px 4px 0 0;
  height: auto;
}
.col.inactive,
.inactive {
  border-radius: 0 0 4px 4px;
  background-color: #dddddd;
  height: max-content;
}
.v-card:not(.item) {
  cursor: auto;
  background-color: #dddddd;
}
.v-card {
  cursor: pointer;
}
.ghost {
  opacity: 0.5;
  background-color: #eeeeee !important;
}
</style>
