# Theme System (Light / Dark / System)

MintHCM supports a UI color-scheme preference that inherits from the OS by default and applies consistently across the Vue frontend and the legacy iframe. This is the "one source of truth" for how the mechanism fits together — read it before touching theming code.

## Preference vs. active theme

`vue/src/store/theme.ts` (`useThemeStore`) deliberately separates two concepts:

- **`preference`** — what the user asked for: `'system'` or the name of a registered Vuetify theme (`'light'`, `'dark'`, ...). Persisted in `localStorage` under `mint.theme.preference`.
- **`activeTheme`** — the theme actually applied. Always one of the themes registered in Vuetify, never `'system'`. Computed from `preference`, falling back to the OS-detected `systemTheme` (via `window.matchMedia('(prefers-color-scheme: dark)')`) when `preference === 'system'` or points at an unregistered theme.

Adding a theme beyond light/dark means registering it in `vue/src/plugins/vuetify.ts` (`theme.themes`) and adding it to `RESOLVED_THEMES` in `theme.ts` — no changes needed to the preference/active-theme split itself.

## Initialization and persistence

- On app init, `backend.ts` calls `theme.init(initData.value.preferences?.theme ?? 'system')` with the value returned by the `/init` endpoint, so the preference is available immediately (and mirrored into `localStorage`, since the OS/anti-FOUC scripts below only have `localStorage` to read from before the app boots).
- `theme.setPreference(value)` updates `localStorage`, updates the store, and persists server-side via `POST user/theme` (see [API docs — User Theme Preference](../../api/documentation/17-user-theme.md)).
- `App.vue` watches `activeTheme` and applies it: `vuetifyTheme.change(name)` plus `document.documentElement.setAttribute('data-mint-theme', name)`. The `data-mint-theme` attribute on `<html>` is the single signal consumed by non-Vuetify CSS (see below).

## Anti-FOUC bootstrap

Before Vue (or legacy) has booted, an inline script reads `localStorage['mint.theme.preference']`, resolves it against `prefers-color-scheme`, and sets `data-mint-theme` (plus a `background` fallback) on `<html>` immediately — this avoids a flash of the wrong theme while JS/CSS is still loading. The same snippet exists in two places since there are two independent HTML entry points:

- `vue/index.html` — standalone Vue boot
- `legacy/themes/SuiteP/tpls/_head.tpl` — legacy page boot

Keep both in sync if the resolution logic changes.

## Legacy bridge

The legacy layer doesn't run Pinia, so it's synchronized via `postMessage` across the `LegacyView.vue` iframe boundary:

- **Vue → legacy**: `LegacyView.vue` watches `themeStore.activeTheme` and posts `{ type: 'mint-theme', theme }` to the iframe on every change and on load. The `_head.tpl` bootstrap script listens for this and updates `data-mint-theme` live.
- **Legacy → Vue**: the theme `<select>` added to `legacy/modules/Users/tpls/EditViewFooter.tpl` (User Settings) posts `{ type: 'mint-theme-preference', preference }` to `window.parent` on change (it also calls `POST api/user/theme` directly, since the legacy page itself needs to update before any Vue round-trip). `LegacyView.vue`'s message handler picks this up and calls `themeStore.setPreference(...)`, which fans the change back out to Vuetify and to `localStorage`.

## CSS tokens

`legacy/themes/SuiteP/css/mint-theme-tokens.css` defines `--mint-*` custom properties per `html[data-mint-theme="..."]` block — the single source of truth for color values outside of Vuetify. `dark-mode.css` layers Bootstrap/SuiteP overrides on top, keyed off the same attribute. Vuetify's own colors are configured separately in `vuetify.ts` and must be kept in sync with the token values by hand (see the comments in `mint-theme-tokens.css`).
