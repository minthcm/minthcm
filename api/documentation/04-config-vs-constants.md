# Configuration vs Constants

Understanding the difference between configuration and constants is important for proper application customization.

## Quick Reference

| Aspect | Constants | Configuration |
|--------|-----------|---------------|
| **Location** | `constants/` | `configs/` |
| **Purpose** | Application-wide definitions | Runtime settings |
| **Extensible** | ✅ Yes (via `custom/constants/`) | ❌ No |
| **Examples** | Module icons, menu items, enums | OAuth keys, database settings |
| **File Type** | PHP arrays | Various (PHP, JSON, keys) |
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

Defines modules that should be excluded from global search results (but still indexed in ElasticSearch).

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

**Usage:** Used by ElasticSearch integration to filter out administrative/system modules from user-facing global search.

**Extension:** Add a file `custom/constants/global_search_excluded_modules/my_exclusions.php` to exclude additional modules.

#### 3. legacy_views.php

Maps modules to their legacy view types (list and record views).

**File:** `constants/legacy_views.php`

```php
<?php
return [
    'ACLRoles' => [
        'list' => false,
        'record' => true,
    ],
    'Employees' => [
        'list' => false,  // Uses new Vue list view
        'record' => false, // Uses new Vue record view
    ],
    // ... more modules
];
```

**Usage:** Determines whether to redirect to legacy views or use new Vue-based interfaces. Modules not listed default to new views.

#### 4. list_constants.php

Defines default configuration for list views.

**File:** `constants/list_constants.php`

```php
<?php
return array(
    "config" => array(
        "actions" => ["edit", "view", "delete"],
        "itemsPerPageOptions" => [5, 10, 20, 50, 100, 200, 500, 1000],
        // ... more config
    ),
);
```

**Usage:** Provides default settings for list view functionality, including available actions and pagination options.

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
    "Employees" => "account",
    "Accounts" => "domain",
    "Contacts" => "account-multiple",
    "Leads" => "account-plus",
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

**Usage:** Populates the quick create dropdown menu with frequently used modules.

### How Constants Are Loaded

The `ConstantsLoader` class loads constants and automatically merges custom overrides:

```php
// utils/ConstantsLoader.php
class ConstantsLoader
{
    private const NORMAL_PATH = 'constants/';
    private const CUSTOM_PATH = 'custom/constants/';

    public static function getConstants(string $name): array | false
    {
        $file_path = self::NORMAL_PATH . $name . '.php';
        $custom_files_path = self::CUSTOM_PATH . $name . '/';

        // Load base constants
        if (file_exists($file_path)) {
            $include_content = include $file_path;
            
            // Load custom extensions
            $custom_files = scandir($custom_files_path);
            if (!empty($custom_files)) {
                foreach ($custom_files as $custom_file) {
                    if (substr($custom_file, -4) !== '.php') {
                        continue;
                    }
                    
                    $custom_content = include $custom_files_path . $custom_file;
                    // Merge custom constants with base
                    $include_content = array_merge($include_content, $custom_content);
                }
            }
        } else {
            return false;
        }

        return $include_content;
    }
}
```

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

All files are merged in alphabetical order. Use numeric prefixes to control merge order.

## Configuration

### What Is Configuration?

Configuration files contain **runtime settings** such as:

- Database credentials
- OAuth2 keys
- API secrets
- Environment-specific settings

These are **NOT extensible** via the `custom/` directory and should **NOT** be committed to version control (use `.gitignore`).

### Location

```
api/configs/
├── .gitignore
├── private.key            # OAuth2 private key (RSA)
├── public.key             # OAuth2 public key (RSA)
└── mint/                  # MintHCM-specific configs
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

Database configuration is inherited from the legacy MintHCM installation. Doctrine reads connection settings from:

```
../legacy/config.php
```

### OAuth2 Keys

OAuth2 keys are stored in `configs/`:

```
configs/
├── private.key    # Generated RSA private key
└── public.key     # Generated RSA public key
```

**Generate keys:**

```bash
cd configs
# Generate private key
openssl genrsa -out private.key 2048
# Generate public key
openssl rsa -in private.key -pubout -out public.key
```

**OAuth2 Client Secrets:**

Since version 4.3.0, OAuth2 client secrets (e.g., for the frontend client) are stored in the database table `oauth2clients` and can be regenerated using:

```bash
./MintCLI oauth2:regenerateClientSecret
```

This command:
- Generates a new random secret for the 'frontend' OAuth2 client
- Stores it in the database (plain text, not hashed)
- Does not require manual configuration in frontend `.env` files

### $mint_config — Application Settings

The `$mint_config` global array (defined in `configs/mint/config.php`) holds runtime settings consumed by the API. It is loaded before every request and can be overridden per-environment via `configs/mint/config_override.php` (git-ignored).

#### Available keys

| Key | Default | Description |
|-----|---------|-------------|
| `database` | *(see config.php)* | Doctrine DB connection params (`driver`, `host`, `port`, `dbname`, `user`, `password`, `charset`) |
| `search.default_engine` | `'ElasticSearch'` | Search engine to use |
| `search.default_page_size` | `25` | Default number of items per list page |
| `search.engines.ElasticSearch` | `[['host'=>'localhost','port'=>'9200',...]]` | ElasticSearch node list |
| `oauth2_encryption_key` | `'MintHCM-DEFKEY'` | Encryption key for OAuth2 token payload |
| `session_grant_interval` | `'PT4H'` | OAuth2 access token lifetime (ISO 8601 duration). Controls how long a user stays logged in before re-authentication is required. |

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

> **Note:** Changing `session_grant_interval` only affects **newly issued tokens**. Users logged in before the change will be logged out at their original token expiry; they receive the new TTL on next login.

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

- ✅ Storing secrets (passwords, API keys, OAuth tokens)
- ✅ Environment-specific settings (dev, staging, prod)
- ✅ Database connection strings
- ✅ Third-party service credentials
- ✅ File paths that vary by environment

**Examples:**
- OAuth2 private/public keys
- Database credentials
- SMTP server settings
- External API endpoints

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

1. **Never commit secrets to git**
   ```gitignore
   # configs/.gitignore
   private.key
   public.key
   *.local.php
   ```

2. **Provide example files**
   ```bash
   configs/database.example.php  # Committed
   configs/database.php          # Ignored, created from example
   ```

3. **Use environment variables for sensitive data**
   ```php
   $dbPassword = getenv('DB_PASSWORD') ?: 'default';
   ```

4. **Document configuration requirements**
   ```php
   /**
    * OAuth2 Configuration
    * 
    * Requires:
    * - configs/private.key (RSA 2048-bit)
    * - configs/public.key (generated from private key)
    */
   ```

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
return [
    'Tasks' => translate('LBL_LIST_TITLE', 'Tasks'),
    'Meetings' => translate('LBL_LIST_TITLE', 'Meetings'),
    'Calls' => translate('LBL_LIST_TITLE', 'Calls'),
];
```

### Pattern: Environment-Specific Config

```php
// app/Config/AppConfig.php
public static function getEnvironment()
{
    return getenv('APP_ENV') ?: 'production';
}

public static function isDebug()
{
    return self::getEnvironment() === 'development';
}
```

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

// custom/constants/quick_create/hr_quick_create.php
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
# 1. Verify the constant is loaded
php -r "require 'api/utils/ConstantsLoader.php'; 
        var_dump(MintHCM\Util\ConstantsLoader::getConstants('global_search_excluded_modules'));"

# 2. Rebuild ElasticSearch index
cd api
php bin/console elasticsearch:reindex

# 3. Clear caches
rm -rf api/data/cache/*
```

### Configuration Not Found

**Problem:** Config files not loading

**Solution:**
```bash
# Check file exists
ls -la configs/private.key

# Verify permissions
chmod 600 configs/private.key

# Check paths in code
var_dump(realpath('configs/private.key'));
```

## Next Steps

- Learn about [Routing System](./05-routing.md)
- Understand [Extending the API](./08-extending-api.md)
- Explore [Database Communication](./06-database.md)
