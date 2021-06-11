import Vue from 'vue'
import Kanban from './Kanban.vue'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false

new Vue({
  vuetify,
  render: h => h(Kanban)
}).$mount('#app')
