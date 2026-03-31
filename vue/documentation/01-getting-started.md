# Getting Started

This guide will help you set up the MintHCM frontend development environment and get your first build running.

## Prerequisites

Before you begin, ensure you have:

- **Node.js** - Version21.x (recommended: latest LTS)
- **npm** - Comes with Node.js (version 7+ recommended)
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
# Should output: v21.x.x

# Check npm version
npm --version
# Should output: 7.x.x or higher
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
  VITE v3.x.x  ready in 1234 ms

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

Output will be in the `dist/` directory:

```
dist/
├── assets/          # Bundled JS/CSS with cache busting
├── index.html       # Entry point
└── ...
```

**Build size:** Typically 2-4 MB (minified, gzipped ~800KB)

### Build for Repository

Special build that copies assets to the parent directory:

```bash
npm run build:repo
```

This:
1. Builds the production version
2. Removes old `../assets/` directory
3. Copies new build to parent directory
4. Removes `../index.html` (not needed in MintHCM structure)

## First Steps

### 1. Verify the Setup

After starting the dev server, you should see:

1. **Login screen** if not authenticated
2. **Dashboard** if already logged in
3. No console errors (check browser DevTools)

### 2. Understanding Hot Reload

Make a simple change to test hot reload:

**File:** `src/views/DashboardView/DashboardView.vue`

```vue
<template>
    <div>
        <h1>Dashboard</h1>
        <!-- Add this line: -->
        <p>Hello, MintHCM Developer!</p>
    </div>
</template>
```

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
- `.eslintrc.js` - ESLint rules
- `prettier.config.js` - Code formatting rules

### TypeScript

The project uses TypeScript for type safety:

```bash
# Check TypeScript errors (happens automatically in IDE)
npx vue-tsc --noEmit
```

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

Or set it in `vite.config.ts`:

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
4. Check `vite.config.ts` proxy configuration:

```typescript
server: {
    proxy: {
        '/api': {
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

1. Check TypeScript errors: `npx vue-tsc --noEmit`
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
| `PROXY_URL` | Backend API URL | - | ✅ Yes |
| `NODE_ENV` | Environment mode | `development` | Auto-set |

### Using Environment Variables in Code

```typescript
// In vite.config.ts
export default defineConfig({
    define: {
        'process.env': {
            PROXY_URL: process.env.PROXY_URL ?? '',
        },
    },
})

// Access in code
const proxyUrl = process.env.PROXY_URL
```

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
npm run build:repo       # Build and copy to parent

# Code Quality
npm run lint             # Check code style
npm run lint -- --fix    # Auto-fix issues

# Preview Production Build
npm run serve            # Preview production build locally
```

---

**Ready to code?** Head to [Project Architecture](./02-architecture.md) to understand the technology stack! 🚀
