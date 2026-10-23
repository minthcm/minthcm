<?php
/**
 * MintHCM
 *
 * Admin view metadata for Language Packs management.
 * This file defines the panels rendered by the Vue AdminView
 * at /#/Administration/LanguageSettings.
 *
 * Structure mirrors other admins/ metadata files so MintPanel components can be reused.
 */

$viewdefs['Administration']['LanguageSettings'] = [
    'order' => ['languages'],
    'panels' => [
        'languages' => [
            'component' => 'MintPanelLanguages',
            'data' => [],
        ],
    ],
];
