# Configuration vs Constants

Understanding the difference between configuration and constants is important for proper application customization.

## Quick Reference

| Aspect | Constants | Configuration |
|--------|-----------|---------------|
| **Location** | `constants/` | `configs/` |
| **Purpose** | Application-wide definitions | Runtime settings |
| **Extensible** | ✅ Yes (via `custom/constants/`) | Per instance only (`configs/mint/config_override.php`), not via `custom/` |
| **Examples** | Module icons, menu items, enums | OAuth keys, database settings |
| **File Type** | PHP arrays | PHP (`$mint_config`), RSA key files |
| **When to Use** | UI definitions, app behavior | Secrets, environment-specific |

## Constants

### What Are Constants?

Constants are **application-wide definitions** that define behavior, appearance, and structure. They are:

- Defined in PHP files that return arrays
- Loaded via `ConstantsLoader` class
- **Extensible** via the `custom/constants/` directory
- Version-controlled (committed to git)

### Location

```
api/constants/
├── colored_enum.php                    # Color codes for enum values
├── global_search_excluded_modules.php  # Modules excluded from global search
├── legacy_views.php                    # Legacy view type mappings
├── list_constants.php                  # List view configuration
├── menu_icons.php                      # Menu icon mappings
├── module_icons.php                    # Module icon definitions
└── quick_create.php                    # Quick create configurations
```

### Constants Files Overview

#### 1. colored_enum.php

Defines CSS styles for enum field values based on color names.

**File:** `constants/colored_enum.php`

```php
<?php
return [
    "gray" => "color:#616161; background-color:#dbdbdb;",
    "blue" => "color:#316b95; background-color: #e0f1ff;",
    "yellow" => "color:#5b5800; background-color: #f8f0aa;",
    "green" => "color:#006222; background-color:#e1ffeb;",
    "red" => "color:#b00020; background-color:#ffe0e8;",
    "-default-" => "color:#000000; background-color:#cccccc;",
];
```

**Usage:** Used by enum fields in list and detail views to apply consistent color coding.

#### 2. global_search_excluded_modules.php

Defines modules that should be excluded from global search results. The exclusion is applied at query time (`lib/Search/ElasticSearch/ElasticQuery.php`), so the modules stay indexed and no reindex is needed after a change.

**File:** `constants/global_search_excluded_modules.php`

```php
<?php
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
```

**Usage:** Used by the ElasticSearch query builder to filter out administrative/system modules from user-facing global search.

**Extension:** Add a file `custom/constants/global_search_excluded_modules/my_exclusions.php` to exclude additional modules.

#### 3. legacy_views.php

Maps modules to their legacy view types (list and record views).

**File:** `constants/legacy_views.php`

```php
<?php
return [
    'ACLRoles' => [
        'list' => false,   // Vue list view
        'record' => true,  // legacy record view
    ],
    'Prospects' => [
        'list' => false,
        'record' => true,
    ],
    // ... more modules
];
```

**Usage:** Determines whether the Vue router redirects to legacy views or uses Vue views (`backend.initData.legacy_views`). Keys: `list`, `record`, and optionally `popup` (relate popup, read by `vue/src/views/LegacyView/LegacyEventManager/LegacyEvents/OpenRelatePopup.ts`).

The constant is post-processed in `app/Controllers/Init/Init.php` (`getLegacyViews()`):
- **list** - a module without a `list` entry gets `false` (Vue list) only if it has `metadata/eslistviewdefs.php`; otherwise the value stays undefined and the Vue router falls back to the legacy list view.
- **record** - a module without `metadata/recordviewdefs.php` always gets `true` (legacy record view), even if the constant says `false`.

#### 4. list_constants.php

Defines default configuration for list views.

**File:** `constants/list_constants.php`

```php
<?php
return array(
    "config" => array(
        "actions" => ["edit", "view", "delete"],
        "itemsPerPageOptions" => [5, 10, 20, 50, 100, 200, 500, 1000],
        "defaultMaxItemsPerPage" => 20,
        "otherElementsFixedHeight" => 360,
    ),
    'variables' => array(
        "font" => array(/* primary, secondary */),
        "color" => array(/* primary, secondary, light, dark, ... */),
        // ...
    ),
);
```

**Usage:** Provides default settings for list view functionality (available actions, pagination options, default page size) and the `variables` block (fonts, colors). Read by `ListInitController` and the ElasticSearch list classes.

#### 5. menu_icons.php

Maps menu action names to icon names.

**File:** `constants/menu_icons.php`

```php
<?php
return array(
    "add" => 'plus',
    "create" => 'plus',
    "view" => 'view-list',
    // ... more mappings
);
```

**Usage:** Used to display consistent icons in navigation menus and action buttons.

#### 6. module_icons.php

Maps module names to their display icons.

**File:** `constants/module_icons.php`

```php
<?php
return array(
    "Meetings" => "account-group",
    "Notes" => "note",
    "Calls" => "phone",
    "Employees" => "account-tie",
    // ... more modules
);
```

**Usage:** Used throughout the UI to display module-specific icons in navigation, headers, and lists.

#### 7. quick_create.php

Defines which modules appear in the quick create menu.

**File:** `constants/quick_create.php`

```php
<?php
return array(
    "Ideas" => translate("LBL_LIST_TITLE", "Ideas"),
    "Kudos" => translate("LBL_LIST_TITLE", "Kudos"),
    "Notes" => translate("LBL_LIST_TITLE", "Notes"),
    // ... more modules
);
```

**Usage:** Populates the quick create dropdown menu with frequently used modules (only modules available to the user are shown).

**Important:** `quick_create` is loaded with `ConstantsLoader::getConstants('quick_create', true)` (`app/Controllers/Init/Init.php`). If any file exists in `custom/constants/quick_create/`, the custom entries **replace** the core list instead of being merged - list every module you want in the menu.

### How Constants Are Loaded

The `ConstantsLoader` class loads constants and automatically merges custom overrides:

```php
// utils/ConstantsLoader.php
class ConstantsLoader
{
    private const NORMAL_PATH = 'constants/';
    private const CUSTOM_PATH = 'custom/constants/';

    public static function getConstants(string $name, bool $only_custom_if_exist = false): array | false
    {
        $file_path = self::NORMAL_PATH . $name . '.php';
        $custom_files_path = self::CUSTOM_PATH . $name . '/';

        if (file_exists($file_path)) {
            $core_include_content = include $file_path;
            $include_content = [];

            // Collect custom extensions (all *.php files, alphabetical order)
            if (is_dir($custom_files_path)) {
                $custom_files = scandir($custom_files_path);
                if (!empty($custom_files)) {
                    foreach ($custom_files as $custom_file) {
                        if (substr($custom_file, -4) !== '.php') {
                            continue;
                        }
                        $loaded_custom_content = include $custom_files_path . $custom_file;
                        $include_content = array_merge($include_content, $loaded_custom_content);
                    }
                }
            }

            // Merge with core unless $only_custom_if_exist is set and custom files returned data
            if (!($only_custom_if_exist && !empty($include_content))) {
                $include_content = array_merge($core_include_content, $include_content);
            }
        } else {
            return false;
        }

        return $include_content;
    }
}
```

With `$only_custom_if_exist = true`, non-empty custom content replaces the core constants entirely (used for `quick_create`). Paths are relative, so the working directory must be `api/`.

### Using Constants in Code

```php
// Load module icons
$moduleIcons = ConstantsLoader::getConstants('module_icons');
$icon = $moduleIcons['Employees'] ?? 'default';

// Load global search exclusions
$excludedModules = ConstantsLoader::getConstants('global_search_excluded_modules');
$isExcluded = in_array('Currencies', $excludedModules);

// Load colored enum styles
$coloredEnum = ConstantsLoader::getConstants('colored_enum');
$style = $coloredEnum['green'] ?? $coloredEnum['-default-'];

// Load legacy view mappings
$legacyViews = ConstantsLoader::getConstants('legacy_views');
$useLegacyList = $legacyViews['Employees']['list'] ?? false;
```

## Extending Constants

To add or override constants, create files in the `custom/constants/{constant_name}/` directory:

### Example 1: Adding Custom Module Icons

**Step 1:** Create the custom constants directory

```bash
mkdir -p custom/constants/module_icons
```

**Step 2:** Create a custom file

**File:** `custom/constants/module_icons/my_custom_icons.php`

```php
<?php
return [
    'MyCustomModule' => 'star',
    'Employees' => 'account-tie',  // Override existing icon
];
```

**Result:** When `ConstantsLoader::getConstants('module_icons')` is called:
1. Base constants loaded from `constants/module_icons.php`
2. Custom constants loaded from `custom/constants/module_icons/my_custom_icons.php`
3. Arrays merged (custom values override base values)

### Example 2: Excluding Additional Modules from Global Search

**Step 1:** Create the custom constants directory

```bash
mkdir -p custom/constants/global_search_excluded_modules
```

**Step 2:** Create a custom exclusion file

**File:** `custom/constants/global_search_excluded_modules/custom_exclusions.php`

```php
<?php
return [
    'MyInternalModule',
    'TemporaryData',
    'SystemLogs',
];
```

**Result:** These modules will be added to the global search exclusion list alongside the default exclusions.

### Multiple Custom Files

You can have multiple custom constant files:

```
custom/constants/module_icons/
├── 01-company-icons.php
├── 02-hr-icons.php
└── 99-overrides.php
```

All files are merged in alphabetical order. Use numeric prefixes to control merge order. String keys are overridden by later files; list-style constants (numeric keys, e.g. `global_search_excluded_modules`) are appended.

## Configuration

### What Is Configuration?

Configuration files contain **runtime settings** such as:

- Database credentials
- OAuth2 keys
- API secrets
- Environment-specific settings

These are **NOT extensible** via the `custom/` directory. Instance values (keys, `config_override.php`) are git-ignored; only the defaults file `configs/mint/config.php` is committed.

### Location

```
api/configs/
├── .gitignore             # *.key, /*/*, !/*/config.php
├── private.key            # OAuth2 private key (RSA, git-ignored)
├── public.key             # OAuth2 public key (RSA, git-ignored)
└── mint/
    ├── config.php         # $mint_config defaults (committed); includes every other file in mint/
    └── config_override.php # Instance settings (git-ignored, written by the installer)
```

### AppConfig Class

The `AppConfig` class provides application-wide configuration values:

```php
// app/Config/AppConfig.php
namespace MintHCM\Api\Config;

class AppConfig
{
    public static function getBasePath()
    {
        $appBasePath = "/api";
        $currentDirectoryName = dirname($_SERVER['PHP_SELF']);
        
        if (str_contains($currentDirectoryName, '/api') 
            && $appBasePath != $currentDirectoryName) {
            $appBasePath = $currentDirectoryName;
        }
        
        return $appBasePath;
    }
}
```

### Using Configuration

```php
use MintHCM\Api\Config\AppConfig;

$basePath = AppConfig::getBasePath();
```

### Database Configuration

Doctrine does **not** read `legacy/config.php`. Its connection settings come from `$mint_config['database']` (`DoctrineContainerBuilder::addSettings()`), with defaults in `configs/mint/config.php` and the real credentials written by the installer to:

```
api/configs/mint/config_override.php
```

### OAuth2 Keys

OAuth2 keys are stored in `configs/`:

```
configs/
├── private.key    # Generated RSA private key
└── public.key     # Generated RSA public key
```

**Generate keys** (from the MintHCM root):

```bash
./MintCLI oauth2:createKeys
```

Or manually:

```bash
cd api/configs
openssl genrsa -out private.key 2048
openssl rsa -in private.key -pubout -out public.key
chmod 600 private.key public.key
chown www-data:www-data private.key public.key
```

**OAuth2 Client Secrets:**

Since version 4.3.0, OAuth2 client secrets (e.g., for the frontend client) are stored in the database table `oauth2clients` and can be regenerated using:

```bash
./MintCLI oauth2:regenerateClientSecret
```

This command:
- Generates a new random secret for the 'frontend' OAuth2 client
- Stores only its SHA-256 hash in the database (`legacy/MintCLI/src/Services/OAuth2Service.php`)
- Does not require manual configuration in frontend `.env` files

### $mint_config — Application Settings

The `$mint_config` global array (defined in `configs/mint/config.php`) holds runtime settings consumed by the API. It is loaded before every request (composer `autoload.files`) and can be overridden per-environment via `configs/mint/config_override.php` (git-ignored). `config.php` includes every other file in `configs/mint/`, in alphabetical order.

#### Available keys

| Key | Default | Description |
|-----|---------|-------------|
| `database` | *(see config.php)* | Doctrine DB connection params (`driver`, `host`, `port`, `dbname`, `user`, `password`, `charset`) |
| `search.default_engine` | `'ElasticSearch'` | Search engine to use |
| `search.default_page_size` | `25` | Default number of items per list page |
| `search.engines.ElasticSearch` | `[['host'=>'localhost','port'=>'9200',...]]` | ElasticSearch node list |
| `oauth2_encryption_key` | `'MintHCM-DEFKEY'` | Encryption key for OAuth2 token payload |
| `session_grant_interval` | `'PT4H'` (config.php; code fallback `Server::GRANT_INTERVAL = 'PT1H'`) | OAuth2 access token lifetime (ISO 8601 duration). A browser session refreshes it silently, so it does not end the session – see `session_idle_timeout` / `session_absolute_timeout` below. |

#### Overriding settings per environment

Create or edit `configs/mint/config_override.php` (never committed to git):

```php
<?php
// configs/mint/config_override.php

// Extend session to 8 hours on this instance
$mint_config['session_grant_interval'] = 'PT8H';

// Or shorten to 30 minutes for high-security environments
// $mint_config['session_grant_interval'] = 'PT30M';
```

Valid ISO 8601 duration examples: `PT1H` (1 hour), `PT4H` (4 hours), `PT8H` (8 hours), `P1D` (1 day).

> **Note:** Changing `session_grant_interval` only affects **newly issued tokens**. Existing tokens keep their original expiry; browser sessions then get a token with the new TTL on the next silent refresh, while other API clients get it when they request a new token.

#### Reading $mint_config in code

```php
// app/Controllers/OAuth2/Server.php — pattern used for configurable values
public static function getGrantInterval(): string
{
    global $mint_config;
    return $mint_config['session_grant_interval'] ?? self::GRANT_INTERVAL;
}
```

Always provide a sensible fallback (`?? self::GRANT_INTERVAL`) so the application works even if `config_override.php` does not define the key.


### $sugar_config — Security Settings Shared with Legacy

Settings read by both layers (legacy pages and the API) live in `$sugar_config` – set them in `legacy/config_override.php`:

| Key | Default | Description |
|-----|---------|-------------|
| `session_idle_timeout` | `'PT8H'` | Browser session ends after this long without a user request (ISO 8601; `'0'` disables). Polling requests marked with `X-Mint-Background-Request: 1` do not count. |
| `session_absolute_timeout` | `'P7D'` | Browser session ends this long after the login, whatever the activity (ISO 8601; `'0'` disables). |
| `hsts_max_age` | `0` (off) | `Strict-Transport-Security: max-age` sent over HTTPS by legacy pages and the API. Enable only with a valid certificate – browsers refuse plain HTTP for `max-age` seconds. |
| `passwordsetting['legacy_md5_allowed']` | `true` | Accept plain unsalted md5 password hashes (re-hashed to bcrypt on login). Set to `false` after a transition period; accounts still holding md5 then need a password reset. |
| `forgot_password_throttle` | `['max_per_username' => 5, 'max_per_ip' => 20, 'window_minutes' => 15]` | Rate limit of `POST /api/forget_password`. |
| `login_throttle` | `['max_ip_failures' => 20, 'lock_timeout' => 3]` | Login rate limit: failed logins from one IP per lockout period, seconds to wait for the counter lock. |
| `trusted_proxies` | `[]` | Reverse proxy addresses whose `X-Forwarded-For` is trusted by the rate limits. |
| `session_cookie_samesite` | `'Lax'` | `SameSite` attribute of the session cookie. |

Details: [Middlewares → Session and Login Hardening](11-middlewares.md#session-and-login-hardening).

#### Production hardening

- **Serve MintHCM over HTTPS only.** Configure TLS in the web server (or the reverse proxy in front of it) and redirect all plain HTTP requests to HTTPS. The session cookie gets the `Secure` flag automatically on HTTPS requests.
- **HSTS** is off by default, because on an instance without a valid certificate it locks users out. Once HTTPS works, enable it with `hsts_max_age`, starting with a short value and raising it when everything works: `$sugar_config['hsts_max_age'] = 300;` → `31536000` (one year). Behind a reverse proxy that terminates TLS, set the header in the proxy instead.
- **Error details** of the API (file, line, stack trace) are returned only when `$sugar_config['developerMode']` is on – keep it off in production.

## When to Use Constants vs Configuration

### Use Constants When:

- ✅ Defining UI elements (icons, colors, labels)
- ✅ Application behavior that should be version-controlled
- ✅ Values that need to be extended by customizations
- ✅ Enumerations, mappings, list definitions
- ✅ Module-specific default settings
- ✅ Search and filtering behavior
- ✅ View routing rules

**Examples:**
- Module icon mappings (`module_icons.php`)
- Enum color codes (`colored_enum.php`)
- Quick create field lists (`quick_create.php`)
- Default view configurations (`list_constants.php`)
- Global search exclusions (`global_search_excluded_modules.php`)
- Legacy view mappings (`legacy_views.php`)
- Menu icon mappings (`menu_icons.php`)

### Use Configuration When:

- ✅ Storing secrets (passwords, encryption keys)
- ✅ Environment-specific settings
- ✅ Database connection settings
- ✅ Search engine hosts

**Examples:**
- OAuth2 private/public keys (`configs/*.key`)
- Database credentials (`$mint_config['database']`)
- ElasticSearch hosts (`$mint_config['search']`)
- OAuth2 encryption key and token lifetime (`oauth2_encryption_key`, `session_grant_interval`)

Settings shared with legacy (session timeouts, throttling, HSTS, ...) belong in `$sugar_config` (`legacy/config_override.php`), see above.

## Best Practices

### Constants

1. **Keep constants organized by purpose**
   ```
   constants/
   ├── module_icons.php                    # Module icon mappings
   ├── menu_icons.php                      # Menu action icons
   ├── colored_enum.php                    # Enum color styles
   ├── quick_create.php                    # Quick create menu
   ├── list_constants.php                  # List view config
   ├── legacy_views.php                    # View routing rules
   └── global_search_excluded_modules.php  # Search exclusions
   ```

2. **Use descriptive keys**
   ```php
   // ✅ Good
   'Employees' => 'account-tie'
   
   // ❌ Bad
   'emp' => 'at'
   ```

3. **Document complex constants**
   ```php
   <?php
   /**
    * Modules excluded from global search results.
    * These modules are still indexed but won't appear in user-facing search.
    */
   return [
       'OAuthTokens',   // Security: Hide OAuth tokens
       'ACLRoles',      // Admin-only module
   ];
   ```

4. **Extend, don't modify**
   ```bash
   # ✅ Good - create custom file
   custom/constants/module_icons/my_icons.php
   
   # ❌ Bad - modify core file
   constants/module_icons.php
   ```

5. **Use consistent naming in custom directories**
   ```bash
   # Organize custom constants by feature/client
   custom/constants/module_icons/
   ├── 01-hr-modules.php          # HR-related icons
   ├── 02-sales-modules.php       # Sales-related icons
   └── 99-client-overrides.php    # Client-specific overrides
   ```

### Configuration

1. **Never commit secrets to git** - `configs/.gitignore` already ignores them:
   ```gitignore
   *.key
   /*/*
   !/*/config.php
   ```

2. **Keep defaults in `config.php`, instance values in `config_override.php`** - add new keys with a default to `configs/mint/config.php` and override them per instance.

3. **Always read keys with a fallback** - `$mint_config['my_key'] ?? self::DEFAULT` (see `Server::getGrantInterval()` above).

## Common Patterns

### Pattern: Adding Custom Enum Colors

```php
// custom/constants/colored_enum/custom_colors.php
return [
    "purple" => "color:#4a148c; background-color:#e1bee7;",
    "orange" => "color:#e65100; background-color:#ffe0b2;",
];
```

### Pattern: Hiding Module from Global Search

```php
// custom/constants/global_search_excluded_modules/hide_sensitive.php
return [
    'Salaries',
    'PerformanceReviews',
    'InternalAudits',
];
```

### Pattern: Override Legacy View Behavior

```php
// custom/constants/legacy_views/force_new_views.php
return [
    'CustomModule' => [
        'list' => false,    // Use Vue list view
        'record' => false,  // Use Vue record view
    ],
];
```

### Pattern: Custom Quick Create Entries

```php
// custom/constants/quick_create/custom_entries.php
// Replaces the core list entirely - only these modules will be shown
return [
    'Tasks' => translate('LBL_LIST_TITLE', 'Tasks'),
    'Meetings' => translate('LBL_LIST_TITLE', 'Meetings'),
    'Calls' => translate('LBL_LIST_TITLE', 'Calls'),
];
```

### Pattern: Environment-Specific Config

```php
// configs/mint/config_override.php (git-ignored, per instance)
$mint_config['search']['default_page_size'] = 50;
$mint_config['session_grant_interval'] = 'PT8H';
```

Developer mode in the API follows `$sugar_config['developerMode']` (Doctrine dev mode, error details in `MintExceptionHandler`); there is no separate API environment setting.

### Real-World Example: Complete Custom Constants Setup

```php
// custom/constants/module_icons/hr_modules.php
return [
    'Employees' => 'account-tie',
    'Recruitment' => 'account-search',
    'Onboarding' => 'account-plus',
];

// custom/constants/colored_enum/status_colors.php
return [
    "active" => "color:#006622; background-color:#e1ffeb;",
    "inactive" => "color:#b00020; background-color:#ffe0e8;",
];

// custom/constants/global_search_excluded_modules/hr_exclusions.php
return [
    'SalaryHistory',
    'PerformanceNotes',
];

// custom/constants/quick_create/hr_quick_create.php (replaces the core quick create list)
return [
    'Employees' => translate('LBL_LIST_TITLE', 'Employees'),
    'Candidates' => translate('LBL_LIST_TITLE', 'Candidates'),
];
```

## Troubleshooting

### Constants Not Loading

**Problem:** Custom constants not appearing

**Solution:**
```bash
# Check directory structure
ls -la custom/constants/module_icons/

# Verify file returns array
php -r "var_dump(include 'custom/constants/module_icons/my_icons.php');"

# Check file naming (must end in .php)
mv my_icons.txt my_icons.php
```

**Problem:** Custom constants not merging correctly

**Solution:**
```php
// Verify your custom file returns an array (not echo or var_dump)
<?php
// ✅ Good
return [
    'MyModule' => 'icon-name',
];

// ❌ Bad - doesn't return
<?php
$icons = [
    'MyModule' => 'icon-name',
];
```

**Problem:** Module still appears in global search after exclusion

**Solution:**
```bash
# Verify the constant is loaded (run from api/ - paths are relative)
cd api
php -r "require 'utils/ConstantsLoader.php';
        var_dump(MintHCM\Utils\ConstantsLoader::getConstants('global_search_excluded_modules'));"
```

No reindex or cache clearing is needed: the exclusion is applied when the search query is built. If the module still appears, check the module name (it must match the `$beanList` key) and that the file returns an array.

### Configuration Not Found

**Problem:** Config files not loading

**Solution:**
```bash
# Check the key files exist (from api/)
ls -la configs/private.key configs/public.key

# Verify permissions and ownership
chmod 600 configs/private.key configs/public.key
chown www-data:www-data configs/private.key configs/public.key

# Check the instance config is present
ls -la configs/mint/config_override.php
```

## Next Steps

- Learn about [Routing System](./05-routing.md)
- Understand [Extending the API](./08-extending-api.md)
- Explore [Database Communication](./06-database.md)
