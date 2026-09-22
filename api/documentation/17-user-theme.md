# User Theme Preference

The user's color-scheme preference (`system`, `light`, `dark`) is stored server-side alongside the other global user preferences and exposed through a single dedicated endpoint. This document covers the API side; see the frontend docs for the store, Vuetify integration, and the legacy CSS bridge.

## Storage

The preference is not a first-class column — it lives inside the same `global` category `UserPreferences` record as the rest of the user's settings (date format, timezone, etc.), under the `theme` key:

```php
$contents = $pref->getContentsAsArray();
$contents['theme'] = $theme;
$pref->contents = base64_encode(serialize($contents));
```

`Preferences::getUserPreferences()` (`api/app/Controllers/Init/Preferences.php`) returns it as part of the `/init` payload, defaulting to `'system'` when unset:

```php
'theme' => $this->user_preferences['global']['theme'] ?? 'system',
```

## Endpoint

```
POST /user/theme
auth: true
body: { "theme": "system" | "light" | "dark" }
```

Registered in `api/app/Routes/routes/user.php`, handled by `UserThemeController::save()` (`api/app/Controllers/User/UserThemeController.php`). The controller validates against an allowlist (`ALLOWED_THEMES`) and returns `422` on an unknown value; on success it persists the preference and returns `{ "theme": "..." }`.

To add a new theme option beyond `system`/`light`/`dark`, extend `ALLOWED_THEMES` here as well as the Vuetify theme registration and the legacy CSS tokens (see the frontend docs).

## Legacy session sync

Legacy code (`Save.php` and friends) periodically re-serializes the whole preferences array from `$_SESSION` back to the database, which would silently revert an API-side save made moments earlier in the same session. `UserThemeController::save()` writes the new value into `$_SESSION["{$userName}_PREFERENCES"]['global']['theme']` right after persisting, so the two write paths don't fight each other.
