# Getting Started

This guide will help you set up the MintHCM frontend development environment and get your first build running.

## Prerequisites

Before you begin, ensure you have:

- **Node.js** - `^26.12.1 || ^24.22.1` (the `engines` field in `vue/package.json`). Node 26 LTS is recommended; the repository-root `.nvmrc` pins `26.12.1`, so `nvm use` run from the repository root selects it
- **npm** - The version bundled with that Node release
- **Git** - For version control
- **Code Editor** - VS Code recommended with these extensions:
  - Volar (Vue Language Features)
  - TypeScript Vue Plugin
  - ESLint
  - Prettier

### Checking Your Environment

```bash
# Check Node.js version
node --version
# Should output: v26.x.x (>= 26.12.1) or v24.x.x (>= 24.22.1)

# Check npm version
npm --version
```

## Installation

### 1. Navigate to Vue Directory

```bash
cd ./vue
```

### 2. Install Dependencies

```bash
npm install
```

This will install all required packages from `package.json`, including:
- Vue 3 and Vue Router
- Vuetify (Material Design components)
- Pinia (state management)
- Axios (HTTP client)
- TypeScript and build tools

**Installation time:** Usually 2-5 minutes depending on your connection.

### 3. Configure Environment

Create your environment configuration file:

```bash
cp .env.example .env
```

Edit `.env` and configure:

```bash
# Backend API URL (adjust to your instance)
PROXY_URL=http://localhost:8080/<instance_name>
```


**PROXY_URL** should point to your MintHCM backend instance. Common configurations:

- **Local development**: `http://localhost:8080/<instance_name>`
- **Docker**: `http://localhost/<instance_name>` (internal Docker network)
- **Remote server**: `https://your-server.com`

## Running the Application

### Development Mode (Standard)

Start the Vite development server:

```bash
npm run dev
```

You should see output like:

```
  VITE v6.x.x  ready in 1234 ms

  ➜  Local:   http://localhost:5173/
  ➜  Network: use --host to expose
```

Open `http://localhost:5173` in your browser. The app will:
- Hot reload on file changes
- Proxy API requests to your backend (configured in PROXY_URL)
- Show detailed error messages and Vue DevTools integration

### Development Mode (Docker/Network Access)

If working with Docker or need network access:

```bash
npx vite --host 0.0.0.0
```

This exposes the dev server to your local network.

### Production Build

Create an optimized production build:

```bash
npm run build
```

Output will be in the `vue/dist/` directory:

```
dist/
├── assets/          # Bundled JS/CSS with cache busting
├── index.html       # Entry point
├── favicon.ico      # From public/
└── bg.jpg           # From public/
```

`vue/dist/` is committed to the repository and served in place: the root `.htaccess` sets `DirectoryIndex vue/dist/index.html` and rewrites `assets/*` to `vue/dist/assets/*`. There is no copy step.

**Build size:** Typically 2-4 MB (minified, gzipped ~800KB)

### Build for Repository

```bash
npm run build:repo
```

`build:repo` is an alias of `npm run build` (see `package.json`). It is kept for compatibility with existing scripts and does not copy files anywhere.

## First Steps

### 1. Verify the Setup

After starting the dev server, you should see:

1. **Login screen** if not authenticated
2. **Dashboard** if already logged in
3. No console errors (check browser DevTools)

### 2. Understanding Hot Reload

Make a simple change to test hot reload:

**File:** `src/views/AuthView/AuthViewLogin.vue` (the login form, rendered at `#/auth/login` when you are logged out)

Add a line to its template, for example `<p>Hello, MintHCM Developer!</p>`.

> Note: the dashboard (`/`) is rendered by `LegacyView` (an iframe with the legacy Home page), so `src/views/DashboardView/DashboardView.vue` is not used by any route and editing it shows nothing.

Save the file. The browser should update automatically without full page reload.

### 3. Explore the Code

Key files to understand:

- `src/main.ts` - Application entry point
- `src/App.vue` - Root component
- `src/router/index.ts` - Route configuration
- `src/store/backend.ts` - Main application state

### 4. Open Vue DevTools

Install [Vue DevTools](https://devtools.vuejs.org/) browser extension to:
- Inspect component hierarchy
- View Pinia store state
- Monitor events and performance
- Debug router navigation

## Development Tools

### ESLint & Prettier

Code quality is enforced by ESLint and formatted by Prettier:

```bash
# Check for linting errors
npm run lint

# Auto-fix linting issues
npm run lint -- --fix
```

**Configuration files:**
- `.eslintrc.js` - ESLint rules; the Prettier options (no semicolons, single quotes, 4-space indent, `printWidth: 120`) are set in its `prettier/prettier` rule. There is no separate Prettier config file.

### TypeScript

The project uses TypeScript for type safety:

Type errors are reported by the IDE (Volar). `vue-tsc` is not a project dependency and `vite build` does not type-check, so a type error does not fail the build.

**Configuration:** `tsconfig.json`

### Vite Dev Server Features

The Vite dev server provides:

- **Fast startup** - No bundling in dev mode
- **Hot Module Replacement (HMR)** - Instant updates
- **API Proxy** - Forwards `/api` and `/legacy` to backend
- **Source maps** - Debug original TypeScript code
- **Error overlay** - Shows errors in browser

## Common Tasks

### Adding a New Dependency

```bash
# Install a package
npm install package-name

# Install as dev dependency
npm install -D package-name

# Example: Add lodash
npm install lodash
npm install -D @types/lodash
```

### Updating Dependencies

```bash
# Check for outdated packages
npm outdated

# Update all packages (be careful!)
npm update

# Update specific package
npm install package-name@latest
```

### Clearing Cache

If you experience build issues:

```bash
# Remove node_modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear Vite cache
rm -rf node_modules/.vite
```

## Troubleshooting

### Port Already in Use

If port 5173 is already taken:

```bash
# Use a different port
npm run dev -- --port 3000
```

Or set it in `vite.config.mts`:

```typescript
server: {
    port: 3000,
}
```

### PROXY_URL Not Working

**Problem:** API requests fail with CORS errors or 404

**Solutions:**

1. Check `.env` file exists and has correct PROXY_URL
2. Restart dev server after changing `.env`
3. Verify backend is running at the specified URL
4. Check `vite.config.mts` proxy configuration (both `/api` and `/legacy` are proxied):

```typescript
server: {
    proxy: {
        '/api': {
            target: process.env.PROXY_URL ?? '',
            changeOrigin: true,
        },
        '/legacy': {
            target: process.env.PROXY_URL ?? '',
            changeOrigin: true,
        },
    },
}
```

### TypeScript Errors in IDE

**Problem:** Red squiggly lines in `.vue` files

**Solutions:**

1. Install recommended VS Code extensions (Volar)
2. Disable Vetur if installed (conflicts with Volar)
3. Restart TypeScript server: `Cmd/Ctrl + Shift + P` → "TypeScript: Restart TS Server"

### Build Fails

**Problem:** `npm run build` fails

**Solutions:**

1. Check the Node version against `engines` (`node --version`)
2. Fix any ESLint errors: `npm run lint`
3. Clear cache and rebuild:
   ```bash
   rm -rf node_modules/.vite dist
   npm run build
   ```

### Hot Reload Not Working

**Problem:** Changes don't appear in browser

**Solutions:**

1. Check browser console for errors
2. Ensure file is saved (check editor)
3. Hard refresh browser: `Ctrl + Shift + R` (Windows/Linux) or `Cmd + Shift + R` (Mac)
4. Restart dev server

## Environment Variables

Available environment variables:

| Variable | Description | Default | Required |
|----------|-------------|---------|----------|
| `PROXY_URL` | Backend URL the dev server proxies `/api` and `/legacy` to | - | ✅ Yes (dev server only) |
| `CLIENT_SECRET` | Legacy OAuth2 client secret, exposed as `process.env.CLIENT_SECRET` | `''` | No - not read by app code since 4.3.0 (see below) |
| `NODE_ENV` | Environment mode | `development` | Auto-set |

### Using Environment Variables in Code

`vite.config.mts` loads `.env` with `loadEnv()` and exposes to application code only what is listed in `define`. Today that is just `CLIENT_SECRET`:

```typescript
// vite.config.mts
define: {
    'process.env': {
        CLIENT_SECRET: process.env.CLIENT_SECRET ?? '',
    },
},
```

`PROXY_URL` is used only by the dev-server proxy and is **not** available in application code. To expose another variable, add it to `define` in the same way.

**OAuth2 Client Secret Handling:**

Since version 4.3.0, the client secret is automatically retrieved from the backend during login:

```typescript
// In auth store (example)
const internalTokenResponse = await mintApi.post('getInternalFrontendToken', {}, { rawError: true });
if (!internalTokenResponse.data?.client_secret) {
    return false
}
const response = await mintApi.post('login', {
    client_secret: internalTokenResponse.data.client_secret,
    username,
    password,
    login_language: languages.currentLanguage ?? 'pl_PL',
})
```

⚠️ **Security Note:** Never commit `.env` file to version control. Use `.env.example` as a template.

## Next Steps

Now that your environment is set up:

1. 📖 Read [Project Architecture](./02-architecture.md) to understand the stack
2. 📁 Explore [Directory Structure](./03-directory-structure.md)
3. 🎨 Learn [Customization Guide](./11-customization.md) to start developing

## Quick Reference

```bash
# Development
npm run dev              # Start dev server
npm run dev -- --host    # Expose to network

# Production
npm run build            # Build for production
npm run build:repo       # Alias of npm run build

# Code Quality
npm run lint             # Check code style
npm run lint -- --fix    # Auto-fix issues

# Preview Production Build
npm run serve            # Preview production build locally
```

---

**Ready to code?** Head to [Project Architecture](./02-architecture.md) to understand the technology stack! 🚀
