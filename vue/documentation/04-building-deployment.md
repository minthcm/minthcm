# Building & Deployment

This guide covers how to build the MintHCM frontend for development and production environments.

## Requirements

- **Node.js** `^26.12.1 || ^24.22.1` (`engines` in `vue/package.json`; the repository-root `.nvmrc` pins `26.12.1`)
- Dependencies installed with `npm install` (or `npm ci`) in `vue/`

## Build Modes

### Development Build

Development mode provides the best developer experience with:
- ⚡ **Instant startup** - No bundling, native ESM
- 🔥 **Hot Module Replacement (HMR)** - Changes appear instantly
- 🐛 **Source maps** - Debug original TypeScript code
- 📝 **Detailed errors** - Full stack traces and warnings

**Start development server:**

```bash
npm run dev
```

**Output:**

```
VITE v6.x.x  ready in 450 ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
➜  press h + enter to show help
```

**What happens:**

1. Vite starts HTTP server on port 5173 (Vite default; not set in `vite.config.mts`)
2. No bundling - serves files as native ES modules
3. Proxies `/api` and `/legacy` to backend (from `PROXY_URL` in `.env`)
4. Watches files for changes
5. Hot-reloads components on save

**Development server features:**

- **Fast refresh** - Components update without page reload
- **Error overlay** - Errors shown in browser
- **Vue DevTools** - Full debugging support
- **Source maps** - Debug TypeScript directly

### Development Build (Network Access)

Expose dev server to your local network (useful for Docker, mobile testing):

```bash
npm run dev -- --host 0.0.0.0
```

Or use Vite directly:

```bash
npx vite --host 0.0.0.0
```

**Output:**

```
➜  Local:   http://localhost:5173/
➜  Network: http://192.168.1.100:5173/
```

Now accessible from other devices on your network.

### Production Build

Production build creates optimized, minified files ready for deployment:

```bash
npm run build
```

**What happens:**

```
1. TypeScript → JavaScript (transpiled by esbuild, no type-check)
   ↓
2. Vue SFC compilation
   ↓
3. SCSS → CSS processing
   ↓
4. Tree shaking (remove unused code)
   ↓
5. Code splitting (create chunks)
   ↓
6. Minification (esbuild, Vite default)
   ↓
7. Asset hashing (images, fonts)
   ↓
8. Generate vue/dist/ folder
```

**Build output:**

```
vue/dist/
├── index.html                      # Entry point
├── assets/
│   ├── index-<hash>.js             # Main bundle
│   ├── index-<hash>.css            # Main styles
│   ├── <Chunk>-<hash>.js           # Lazy-loaded chunks (field components, admin view, ...)
│   └── ...                         # Fonts, images, flag SVGs
├── favicon.ico                     # From public/
└── bg.jpg                          # From public/
```

**File naming:**
- Hash in filename (e.g., `C_TSTIPe` in `index-C_TSTIPe.js`) changes when content changes
- Enables long-term caching (new hash = new file)

### `build:repo`

```bash
npm run build:repo
```

`build:repo` is an alias of `npm run build` (`"build:repo": "npm run build"` in `package.json`). It does not copy anything; the output stays in `vue/dist/`.

## Build Configuration

### `vite.config.mts`

Main build configuration (abridged from the real file):

```typescript
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import path from 'path'

// Load .env into process.env for this config
process.env = {
    ...process.env,
    ...loadEnv(process.env.NODE_ENV as string, process.cwd(), ''),
}

export default defineConfig({
    base: './',                     // Relative asset paths (instance can live in a subfolder)

    plugins: [
        vue(),                      // Vue 3 support
        vuetify({                   // Vuetify 4 auto-import
            autoImport: true,
        }),
    ],

    define: {
        'process.env': {
            CLIENT_SECRET: process.env.CLIENT_SECRET ?? '',
        },
    },

    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),  // @ = src/
        },
    },

    build: {
        target: 'esnext',           // Modern browsers only
    },

    server: {
        base: '/',
        proxy: {
            '/api': {               // Proxy API requests
                target: process.env.PROXY_URL ?? '',
                changeOrigin: true,
            },
            '/legacy': {            // Proxy legacy requests
                target: process.env.PROXY_URL ?? '',
                changeOrigin: true,
            },
        },
    },
})
```

`outDir` (`dist`), `assetsDir` (`assets`), `minify` (esbuild) and `sourcemap` (off) use Vite defaults.

### Environment Variables

Environment variables (`.env`, created from `.env.example`):

```bash
# Backend URL for the dev-server proxy
PROXY_URL=http://localhost:8080/minthcm

# Legacy OAuth client secret - kept in .env.example, not read by app code since 4.3.0
CLIENT_SECRET=
```

`PROXY_URL` is used only by the dev server. `CLIENT_SECRET` is still exposed through `define`, but since 4.3.0 the frontend fetches the client secret from the backend at login (`getInternalFrontendToken` in `src/store/auth.ts`). See [Getting Started](./01-getting-started.md#environment-variables).

## Optimization Strategies

### Code Splitting

**Route-based splitting** - lazy route components get their own chunk:

```typescript
// router/routes.ts
{
    path: '/Administration/:viewName',
    name: 'admin-view',
    component: () => import('@/views/AdminView/AdminView.vue'),
    meta: { auth: true, adminOnly: true },
},
```

`ListView`, `RecordView` and `LegacyView` are imported eagerly and are part of the main bundle.

**Component splitting:** `Field.vue` loads every field component with a dynamic `import()`, so each `{type}.{view}.vue` is its own chunk.

### Tree Shaking

Vite automatically removes unused exports from ES modules. Import specific exports (`import { DateTime } from 'luxon'`) rather than whole namespaces.

### Assets

- Images imported from `src/assets/` (e.g. `@/assets/mint_logo.png`) are hashed and emitted to `dist/assets/`; with `base: './'` they are referenced relatively.
- Icon fonts come from `@mdi/font` (imported in `src/plugins/vuetify.ts`); Roboto is loaded from Google Fonts at runtime by `src/plugins/webfontloader.ts`.
- CSS is minified and hashed. No CSS purging is configured.

### Bundle Analysis

To inspect the bundle, temporarily add `rollup-plugin-visualizer` (not a project dependency) to the `plugins` array in `vite.config.mts` and run `npm run build`. Do not commit that change.

## Deployment

### How MintHCM Serves the Frontend

The frontend is **not** copied anywhere. `vue/dist/` is committed to the repository and served in place by Apache through the instance root `.htaccess` (not tracked in git; the template is `legacy/MintCLI/src/Assets/.htaccess`, written during installation and regenerated by upgrade scripts, keeping the instance `RewriteBase`). Relevant rules:

```apache
DirectoryIndex vue/dist/index.html

RewriteRule ^assets/(.*?)$ vue/dist/assets/$1 [L]
RewriteRule ^legacy/(.*?)$ legacy/index.php/$1 [L]
RewriteRule ^api/(.*?)$ api/index.php [L]
RewriteRule ^bg.jpg$ vue/dist/bg.jpg [L]
RewriteRule ^favicon.ico$ vue/dist/favicon.ico [L]

# SPA fallback
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ vue/dist/index.html [L]
```

The API (`api/index.php`) and the legacy application (`legacy/`) are PHP in the same document root, so no reverse proxy is needed. Routing inside the SPA uses hash history (`#/...`).

**Build and deploy:**

```bash
cd vue
npm run build      # or npm run build:repo (alias)
```

Then commit the changed `vue/dist/` together with the source changes, or rebuild on the target server.

### Caching

The root `.htaccess` sends `Cache-Control: no-store, no-cache, must-revalidate` for `index.html`, so a new build is picked up immediately. Hashed files in `assets/` can be cached long-term by the web server.

## Troubleshooting

### Build Fails

**Wrong Node version:** check `node --version` against `engines` (`^26.12.1 || ^24.22.1`); with nvm, run `nvm use` in the repository root.

**TypeScript errors:** `vite build` does not type-check, so type errors do not stop the build; check them in the IDE (Volar). `vue-tsc` is not a project dependency.

**Out of memory:**

```bash
# Increase Node.js memory
NODE_OPTIONS=--max-old-space-size=4096 npm run build
```

### Large Bundle Size

- Look for unnecessary or unused dependencies
- Prefer dynamic `import()` for rarely used views and heavy components
- Use the bundle analysis described above

### Assets Not Loading

- Use the `@/` alias for imports
- Keep `base: './'` in `vite.config.mts` (instances often live in a subfolder)
- Verify the files exist in `vue/dist/assets/` after the build and that the root `.htaccess` contains the `assets` rewrite

## Best Practices

### Development

- ✅ Use the dev server, not the production build
- ✅ Use the Node version from `engines` / `.nvmrc`
- ✅ Use Vue DevTools

### Production

- ✅ Rebuild `vue/dist/` after every frontend change and commit it with the source change
- ✅ Test the build before deploying

## Next Steps

- Learn about [Customization](./11-customization.md)
- Explore the [User Theme System](./14-user-theme.md)
- Understand the [Field System](./09-fields.md)

---

**Build it right, deploy with confidence!** 🚀
