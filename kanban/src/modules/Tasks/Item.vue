<template>
  <VCard
    v-ripple="item.editable || item.detailview"
    :class="{
      'pa-2': true,
      'mb-2': true,
      'high-priority': item.priority === 'High',
      'inactive': !item.editable && !item.detailview,
      pointer: item.editable || item.detailview,
      default: !item.editable && !item.detailview,
      ...classesFromParent
    }"
    @click="$emit('item-click', item)"
  >
    <FullscreenButton @open-fullscreen="() => $emit('open-fullscreen', item)" />

    <p class="ma-0 mb-2 font-weight-bold">{{item.name}}</p>
    <p class="ma-0 mb-2">{{item.assigned_user_name}}</p>
    <p class="ma-0 mb-2">{{item.translated_priority}}</p>
    <p
      v-if="item.date_due"
      :class="{
        'ma-0': true,
        'expired': expired
      }"
    >
      <VIcon>mdi-calendar</VIcon> {{item.date_due | toUserDateTime(this.defs.user_date_format, this.defs.user_time_format)}}
      <VIcon
        v-if="expired"
        class="px-1 alert"
      >
        mdi-alert-circle
      </VIcon>
    </p>
  </VCard>
</template>

<script>
import { VCard, VIcon } from 'vuetify/lib'
import FullscreenButton from '../../components/FullscreenButton.vue'
import moment from 'moment'
require('moment/locale/pl')
moment.locale('pl')

export default {
  name: 'Tasks-Item',
  props: {
    item: Object,
    defs: Object,
    classesFromParent: Object
  },
  components: {
    VCard,
    VIcon,
    FullscreenButton,
  },
  computed: {
    expired () {
      return (!this.defs.expired_excluded_columns || this.defs.expired_excluded_columns.indexOf(this.item.entry_status) === -1) && moment.utc().isAfter(moment.utc(this.item.date_due))
    }
  },
  filters: {
    toUserDateTime (value, dateFormat, timeFormat) {
      return (dateFormat && timeFormat) ? moment.utc(value).local().format(dateFormat + ' ' + timeFormat) : value
    }
  }
}
</script>

<style scoped>
.v-card.high-priority {
  border-left: 3px solid #f05a41;
}
.v-icon {
  color: #3750a0;
}
.v-icon.inactive {
  color: rgba(0, 0, 0, 0.54);
}
p.expired,
p.expired .v-icon,
.v-icon.alert {
  color: #f05a41;
}
.v-icon.alert {
  float: right;
}
.v-card.pointer {
  cursor: pointer;
}
.v-card.default {
  cursor: default;
}
</style>
