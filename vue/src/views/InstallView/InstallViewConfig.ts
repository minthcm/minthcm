import { Component } from 'vue'
import InstallViewLicense from './steps/InstallViewLicense.vue'
import InstallViewEnvironment from './steps/InstallViewEnvironment.vue'
import InstallViewDatabaseConfig from './steps/InstallViewDatabaseConfig.vue'
import InstallViewElasticConfig from './steps/InstallViewElasticConfig.vue'
import InstallViewSiteConfig from './steps/InstallViewSiteConfig.vue'
import InstallViewInstallation from './steps/InstallViewInstallation.vue'

interface InstallConfig {
    steps: InstallStep[]
    db_collations: string[]
}

interface InstallStep {
    title: string
    component: Component
}

export const INSTALL_CONFIG: InstallConfig = {
    steps: [
        {
            title: 'License',
            component: InstallViewLicense,
        },
        {
            title: 'System Environment Check',
            component: InstallViewEnvironment,
        },
        {
            title: 'Database configuration',
            component: InstallViewDatabaseConfig,
        },
        {
            title: 'ElasticSearch configuration',
            component: InstallViewElasticConfig,
        },
        {
            title: 'Site configuration',
            component: InstallViewSiteConfig,
        },
        {
            title: 'Installation',
            component: InstallViewInstallation,
        },
    ],
    db_collations: [
        'utf8mb4_general_ci',
        'utf8mb4_bin',
        'utf8mb4_unicode_ci',
        'utf8mb4_icelandic_ci',
        'utf8mb4_latvian_ci',
        'utf8mb4_romanian_ci',
        'utf8mb4_slovenian_ci',
        'utf8mb4_polish_ci',
        'utf8mb4_estonian_ci',
        'utf8mb4_spanish_ci',
        'utf8mb4_swedish_ci',
        'utf8mb4_turkish_ci',
        'utf8mb4_czech_ci',
        'utf8mb4_danish_ci',
        'utf8mb4_lithuanian_ci',
        'utf8mb4_slovak_ci',
        'utf8mb4_spanish2_ci',
        'utf8mb4_roman_ci',
        'utf8mb4_persian_ci',
        'utf8mb4_esperanto_ci',
        'utf8mb4_hungarian_ci',
        'utf8mb4_sinhala_ci',
        'utf8mb4_german2_ci',
        'utf8mb4_croatian_ci',
        'utf8mb4_unicode_520_ci',
        'utf8mb4_vietnamese_ci',
    ],
}
