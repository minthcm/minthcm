# Building & Deployment

This guide covers how to build the MintHCM frontend for development and production environments.

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
VITE v3.x.x  ready in 450 ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
➜  press h to show help
```

**What happens:**

1. Vite starts HTTP server on port 5173
2. No bundling - serves files as native ES modules
3. Proxies `/api` and `/legacy` to backend (from PROXY_URL)
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
1. TypeScript compilation
   ↓
2. Vue SFC compilation  
   ↓
3. SCSS → CSS processing
   ↓
4. Tree shaking (remove unused code)
   ↓
5. Code splitting (create chunks)
   ↓
6. Minification (reduce file size)
   ↓
7. Asset optimization (images, fonts)
   ↓
8. Generate dist/ folder
```

**Build output:**

```
dist/
├── index.html                      # Entry point
├── assets/
│   ├── index.a1b2c3d4.js          # Main bundle (~800 KB gzipped)
│   ├── index.e5f6g7h8.css         # Styles (~100 KB gzipped)
│   ├── vendor.i9j0k1l2.js         # Third-party libs
│   ├── ListView.m3n4o5p6.js       # Route chunk
│   ├── DetailView.q7r8s9t0.js     # Route chunk
│   └── ...
├── favicon.ico
└── robots.txt
```

**File naming:**
- Hash in filename (e.g., `a1b2c3d4`) changes when content changes
- Enables long-term caching (cache forever, new hash = new file)

**Build statistics:**

```bash
vite build

vite v3.x.x building for production...
✓ 245 modules transformed.
dist/index.html                   0.45 kB
dist/assets/index.a1b2c3d4.js     312.45 kB │ gzip: 98.23 kB
dist/assets/index.e5f6g7h8.css    45.12 kB │ gzip: 12.34 kB
✓ built in 15.43s
```

### Repository Build

Special build for MintHCM's structure (copies to parent directory):

```bash
npm run build:repo
```

**What it does:**

```bash
npm run build &&           # Build production
rm -r ../assets &&         # Remove old assets
cp -r dist/* ../ &&        # Copy to parent
rm ../index.html           # Remove index (not needed in MintHCM)
```

**Result:**

```
/var/www/minthcm/
├── assets/                # Built assets
│   ├── index.a1b2.js
│   └── index.c3d4.css
├── api/                   # Backend
├── legacy/                # Legacy PHP
└── vue/                   # Source (for next rebuild)
```

## Build Configuration

### `vite.config.ts`

Main build configuration:

```typescript
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
    base: './',                    // Use relative paths
    
    plugins: [
        vue(),                      // Vue 3 support
        vuetify({                   // Vuetify auto-import
            autoImport: true,
        }),
    ],
    
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),  // @ = src/
        },
    },
    
    build: {
        target: 'esnext',           // Modern browsers only
        outDir: 'dist',             // Output directory
        assetsDir: 'assets',        // Assets subdirectory
        sourcemap: false,           // No source maps in prod
        minify: 'terser',           // Minification method
    },
    
    server: {
        port: 5173,                 // Dev server port
        proxy: {
            '/api': {               // Proxy API requests
                target: process.env.PROXY_URL,
                changeOrigin: true,
            },
            '/legacy': {            // Proxy legacy requests
                target: process.env.PROXY_URL,
                changeOrigin: true,
            },
        },
    },
})
```

### Environment Variables

Build-time environment variables (`.env`):

```bash
# Backend URL for API proxy
PROXY_URL=http://localhost:8080/minthcm

# OAuth client secret (optional)
CLIENT_SECRET=your_secret_here
```

**Access in code:**

```typescript
// vite.config.ts
export default defineConfig({
    define: {
        'process.env': {
            CLIENT_SECRET: process.env.CLIENT_SECRET ?? '',
        },
    },
})

// In application code
const secret = process.env.CLIENT_SECRET
```

**Environment modes:**

```bash
# Development (default)
npm run dev
# Uses .env

# Production
npm run build
# Uses .env.production (if exists) or .env
```

## Optimization Strategies

### Code Splitting

**Automatic route-based splitting:**

```typescript
// router/routes.ts
const routes = [
    {
        path: '/modules/:module/list',
        // Lazy load - creates separate chunk
        component: () => import('@/views/ListView/ListView.vue')
    }
]
```

**Manual splitting:**

```typescript
// Lazy load heavy component
const TinyMCE = defineAsyncComponent(() =>
    import('@/components/MintWysiwyg.vue')
)
```

**Benefits:**
- Smaller initial bundle
- Faster first paint
- Load features on demand

### Tree Shaking

Vite automatically removes unused code:

```typescript
// utils.ts
export function used() { }
export function unused() { }  // Not imported anywhere

// app.ts
import { used } from './utils'
used()

// Build result: unused() is NOT in final bundle
```

**Tips for better tree shaking:**
- Use ES modules (`import/export`)
- Avoid side effects
- Import specific exports: `import { foo } from 'lib'` not `import * as lib from 'lib'`

### Asset Optimization

**Images:**

```vue
<template>
    <!-- Vite optimizes and hashes images -->
    <img src="@/assets/logo.png" alt="Logo" />
    <!-- Result: <img src="/assets/logo.a1b2c3.png" /> -->
</template>
```

**Fonts:**

```scss
@font-face {
    font-family: 'Roboto';
    // Vite inlines small fonts, hashes large ones
    src: url('@/assets/fonts/roboto.woff2');
}
```

**CSS:**

- Unused CSS is purged
- Critical CSS can be inlined
- Styles are minified and hashed

### Bundle Analysis

Analyze bundle size:

```bash
# Install analyzer
npm install -D rollup-plugin-visualizer

# Add to vite.config.ts
import { visualizer } from 'rollup-plugin-visualizer'

export default defineConfig({
    plugins: [
        visualizer({
            open: true,
            gzipSize: true,
        })
    ]
})

# Build and analyze
npm run build
```

Opens interactive treemap showing what's in your bundle.

## Deployment

### Static File Hosting

The `dist/` folder is a standard static site - deploy anywhere:

**Nginx:**

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/minthcm;
    
    location / {
        try_files $uri $uri/ /index.html;
    }
    
    location /api {
        proxy_pass http://backend:9000;
    }
    
    location /legacy {
        proxy_pass http://backend:9000;
    }
}
```

**Apache:**

```apache
<VirtualHost *:80>
    DocumentRoot /var/www/minthcm
    
    <Directory /var/www/minthcm>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
        
        # SPA routing
        RewriteEngine On
        RewriteBase /
        RewriteRule ^index\.html$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . /index.html [L]
    </Directory>
    
    ProxyPass /api http://backend:9000/api
    ProxyPass /legacy http://backend:9000/legacy
</VirtualHost>
```

### MintHCM Integration

In MintHCM, the built frontend lives in the root:

```
/var/www/minthcm/
├── assets/              # Built Vue assets
├── index.html           # (Not used - PHP handles entry)
├── api/                 # Backend API
├── legacy/              # Legacy PHP application
└── index.php            # PHP entry point
```

**Build and deploy:**

```bash
cd vue
npm run build:repo
```

This automatically:
1. Builds production bundle
2. Copies to parent `assets/`
3. Removes unnecessary `index.html`

### CDN Deployment

For optimal performance, serve assets via CDN:

**1. Build with absolute paths:**

```typescript
// vite.config.ts
export default defineConfig({
    base: 'https://cdn.your-domain.com/assets/',
})
```

**2. Build:**

```bash
npm run build
```

**3. Upload `dist/assets/` to CDN**

**4. Update HTML to reference CDN URLs**

### Caching Strategy

**Recommended HTTP headers:**

```nginx
# Versioned assets (have hash in filename) - cache forever
location /assets/ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}

# index.html - never cache
location = /index.html {
    expires -1;
    add_header Cache-Control "no-cache, no-store, must-revalidate";
}
```

**Why?**
- Hashed assets can be cached forever (hash changes = new file)
- index.html must be fresh (contains references to current hashed assets)

## Performance Optimization

### Lazy Loading

**Routes:**

```typescript
const routes = [
    {
        path: '/admin',
        component: () => import('@/views/AdminView.vue')
    }
]
```

**Components:**

```typescript
import { defineAsyncComponent } from 'vue'

const HeavyComponent = defineAsyncComponent(() =>
    import('@/components/HeavyComponent.vue')
)
```

**Libraries:**

```typescript
// Don't import entire library
import { format } from 'date-fns'  // ✅ Tree-shakeable

// Instead of:
import DateFns from 'date-fns'     // ❌ Imports everything
```

### Compression

Enable gzip/brotli on server:

**Nginx:**

```nginx
gzip on;
gzip_types text/css application/javascript application/json;
gzip_min_length 1000;

# Or use brotli (better compression)
brotli on;
brotli_types text/css application/javascript application/json;
```

**Result:**
- JS bundle: 800 KB → ~200 KB (gzipped)
- CSS: 100 KB → ~25 KB (gzipped)

### Preloading

Preload critical resources:

```html
<!-- In index.html -->
<link rel="preload" href="/assets/index.a1b2.js" as="script">
<link rel="preload" href="/assets/index.c3d4.css" as="style">
```

### Service Worker

For offline support and caching:

```bash
npm install -D vite-plugin-pwa

# vite.config.ts
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
    plugins: [
        VitePWA({
            registerType: 'autoUpdate',
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg}']
            }
        })
    ]
})
```

## Troubleshooting

### Build Fails

**TypeScript errors:**

```bash
# Check TS errors
npx vue-tsc --noEmit

# Fix errors or suppress with:
// @ts-ignore
```

**Out of memory:**

```bash
# Increase Node.js memory
NODE_OPTIONS=--max-old-space-size=4096 npm run build
```

### Large Bundle Size

**1. Analyze bundle:**

```bash
npm install -D rollup-plugin-visualizer
# Add visualizer plugin, rebuild
```

**2. Check for:**
- Unnecessary dependencies
- Imported but unused libraries
- Large images not optimized

**3. Optimize:**
- Use dynamic imports
- Remove unused dependencies
- Compress images

### Slow Build

**1. Check:**
- Number of files
- Large dependencies
- CPU/RAM availability

**2. Optimize:**
```typescript
// vite.config.ts
export default defineConfig({
    build: {
        terserOptions: {
            compress: {
                drop_console: true,  // Remove console.log
            }
        }
    }
})
```

### Assets Not Loading

**Check paths:**
- Use `@/` alias for imports
- Check `base` in `vite.config.ts`
- Verify assets are in `dist/` after build

**CORS issues:**
- Ensure CDN has correct CORS headers
- Check API proxy configuration

## Best Practices

### Development

- ✅ Use dev server, not production build
- ✅ Enable source maps
- ✅ Keep dependencies updated
- ✅ Use Vue DevTools

### Production

- ✅ Minify code
- ✅ Enable compression (gzip/brotli)
- ✅ Set cache headers correctly
- ✅ Test build before deploying
- ✅ Use CDN for assets (optional)

### Continuous Integration

**Example GitHub Actions:**

```yaml
name: Build

on: [push]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: actions/setup-node@v2
        with:
          node-version: '16'
      - run: npm ci
      - run: npm run build
      - uses: actions/upload-artifact@v2
        with:
          name: dist
          path: dist/
```

## Next Steps

- Learn about [Customization](./11-customization.md)
- Explore [Styling & Theming](./12-styling-theming.md)
- Understand [Best Practices](./15-best-practices.md)

---

**Build it right, deploy with confidence!** 🚀
