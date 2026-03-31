<?php

/**
 * Modules excluded from the global search query (but still indexed in ElasticSearch).
 *
 * To exclude additional modules in a custom installation, add a file under:
 *   api/custom/constants/global_search_excluded_modules/
 * returning an array of module names. ConstantsLoader will merge it with this list.
 */
return [
    "Connectors",
    "Currencies",
    "OAuthTokens",
    "OAuthKeys",
    "ACLRoles",
    "ACLActions",
    "EmailMan",
    "Schedulers",
    "SchedulersJobs",
    "CampaignLog",
    "EmailMarketing",
    "AOW_WorkFlow",
];
