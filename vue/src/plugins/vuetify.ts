import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

import { createVuetify } from 'vuetify'
import * as locales from 'vuetify/locale'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

const variables = {
    'top-nav-height': '72px',
    'drawer-width': '460px',
}

const currentLang = localStorage.getItem('currentLang') || 'en_us'
let locale = currentLang.split('_')[0] || 'en'
if (!locales[locale]) {
    locale = 'en'
}

export default createVuetify({
    locale: {
        locale,
        fallback: locale,
        messages: { [locale]: locales[locale] },
    },
    icons: {
        defaultSet: 'mdi',
        sets: { mdi },
        aliases: {
            ...aliases,
            sortAsc: 'mdi-chevron-up',
            sortDesc: 'mdi-chevron-down',
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#00654e',
                    'primary-light': '#e0ecea',
                    'primary-lighter': '#f5fbfa',
                    secondary: '#145d7b',
                    'secondary-dark': '#08384B',
                    'activity': '#7555D6',
                    'on-activity': '#FFFFFF',
                    'workschedule-default': '#000000',
                    'workschedule-delegation': '#7555D6',
                    'workschedule-office': '#00BF49',
                    'workschedule-home': '#008CBF',
                    'workschedule-holiday': '#C3BB00',
                    'workschedule-sick': '#C40024',
                },
                variables,
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#00654e',
                    'primary-light': '#e0ecea',
                    'primary-lighter': '#f5fbfa',
                    secondary: '#145d7b',
                    'secondary-dark': '#08384B',
                    'activity': '#7555D6',
                    'on-activity': '#FFFFFF',
                    'workschedule-default': '#FFFFFF',
                    'workschedule-delegation': '#7555D6',
                    'workschedule-office': '#00BF49',
                    'workschedule-home': '#008CBF',
                    'workschedule-holiday': '#C3BB00',
                    'workschedule-sick': '#C40024',
                },
                variables,
            },
        },
    },
})
