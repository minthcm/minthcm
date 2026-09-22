import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import pinia from './store'
import vuetify from './plugins/vuetify'
import { loadFonts } from './plugins/webfontloader'
import './main.scss'
import '../../legacy/themes/SuiteP/css/mint-theme-tokens.css'

//TODO: dev
import { DateTime } from 'luxon'
import axios from 'axios'
window.DateTime = DateTime
window.axios = axios
window.router = router

loadFonts()

const app = createApp(App)

app.use(router).use(vuetify).use(pinia)

app.mount('#app')
