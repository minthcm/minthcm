import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles/main.css'

import { createVuetify } from 'vuetify'
import * as locales from 'vuetify/locale'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

const variables = {
    'top-nav-height': '72px',
    'drawer-width': '460px',
}

const currentLang = localStorage.getItem('currentLang') || 'en_us'
let locale = currentLang.split('_')[0] || 'en'
if (!(locales as any)[locale]) {
    locale = 'en'
}

export default createVuetify({
    locale: {
        locale,
        fallback: locale,
        messages: { [locale]: (locales as any)[locale] },
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
                    'comment-bg': '#eaf4f2',
                    'on-comment': '#8a8a8a',
                    'comment-hover': '#d0e8e4',
                    'activity': '#7555D6',
                    'on-activity': '#FFFFFF',
                    'workschedule-default': '#000000',
                    'workschedule-delegation': '#7555D6',
                    'workschedule-office': '#00BF49',
                    'workschedule-home': '#008CBF',
                    'workschedule-holiday': '#C3BB00',
                    'workschedule-sick': '#C40024',
                    'duplicate-status-background': '#b0e0cc',
                    'duplicate-status-font': '#42544d',
                },
                variables,
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#00997a',
                    'primary-light': '#0d2e28',
                    'primary-lighter': '#112924',
                    secondary: '#3a9fc4',
                    'secondary-dark': '#1a6a8a',
                    'comment-bg': '#1e3530',
                    'on-comment': '#8ea89e',
                    'comment-hover': '#2a4a42',
                    'activity': '#9b7ee8',
                    'on-activity': '#FFFFFF',
                    'workschedule-default': '#FFFFFF',
                    'workschedule-delegation': '#9b7ee8',
                    'workschedule-office': '#00BF49',
                    'workschedule-home': '#008CBF',
                    'workschedule-holiday': '#C3BB00',
                    'workschedule-sick': '#C40024',
                    'duplicate-status-background': '#b0e0cc',
                    'duplicate-status-font': '#42544d',
                },
                variables,
            },
        },
    },
})
