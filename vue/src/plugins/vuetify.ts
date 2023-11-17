import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

import { createVuetify } from 'vuetify'
import { aliases, mdi } from 'vuetify/iconsets/mdi'

const variables = {
    'top-nav-height': '72px',
    'drawer-width': '460px',
}

export default createVuetify({
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
                },
                variables,
            },
        },
    },
})
