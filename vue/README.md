# MintHCM Frontend Documentation

Welcome to the MintHCM frontend documentation. This guide will help you understand, develop, and customize the Vue.js-based user interface.

## Table of Contents

### Getting Started
1. [Getting Started](./documentation/01-getting-started.md) - Setup, installation, and first steps
2. [Project Architecture](./documentation/02-architecture.md) - Technology stack and design principles
3. [Directory Structure](./documentation/03-directory-structure.md) - Understanding the folder organization
4. [Building & Deployment](./documentation/04-building-deployment.md) - Development and production builds

### Components & Views
5. [Field System](./documentation/09-fields.md) - Dynamic field rendering and customization
6. [Working with Beans](./documentation/10-working-with-beans.md) - CRUD operations and record management

### Customization
7. [Customization Guide](./documentation/11-customization.md) - Extending the frontend via `custom/` directory

---

## Quick Overview

**MintHCM Frontend** is a modern, single-page application (SPA) built with:

- **Vue 3** - Progressive JavaScript framework with Composition API
- **TypeScript** - Type-safe development
- **Vite** - Fast build tool and dev server
- **Vuetify** - Material Design component library
- **Pinia** - State management
- **Vue Router** - Client-side routing
- **Axios** - HTTP client for API communication

### Philosophy

The frontend is designed with these principles:

1. **Component-Based** - Reusable, composable UI components
2. **Type-Safe** - TypeScript for better DX and fewer bugs
3. **Customizable** - `custom/` directory for safe extensions
4. **Performance** - Lazy loading, code splitting, caching
5. **Developer-Friendly** - Clear structure, good documentation

### Project Structure Overview

```
vue/
├── src/
│   ├── components/      # Reusable UI components
│   ├── views/           # Page components (routes)
│   ├── store/           # Pinia state management
│   ├── router/          # Vue Router configuration
│   ├── api/             # Backend API integration
│   ├── composables/     # Reusable composition functions
│   ├── layouts/         # Page layout templates
│   ├── business/        # Business logic (Actions, Logic)
│   ├── custom/          # Your customizations (safe from updates)
│   └── utils/           # Helper functions
├── public/              # Static assets
├── documentation/       # This documentation
└── package.json         # Dependencies and scripts
```

### Key Features

- **Dynamic Field Rendering** - Fields automatically adapt based on type and view mode
- **Module-Based Views** - ListView, DetailView, EditView work for any module
- **Customizable Actions** - Bean Actions, Mass Actions, Subpanel Actions
- **Real-time Updates** - WebSocket support for notifications
- **Responsive Design** - Mobile-friendly UI
- **Internationalization** - Multi-language support
- **Access Control** - ACL-based permissions

### Development Workflow

1. **Start dev server**: `npm run dev`
2. **Make changes** in `src/` (or `src/custom/` for customizations)
3. **See live updates** in browser (hot module replacement)
4. **Build for production**: `npm run build`
5. **Deploy** the `dist/` folder

### Getting Help

- **For setup issues**: See [Getting Started](./documentation/01-getting-started.md)
- **For customization**: See [Customization Guide](./documentation/11-customization.md)
- **For working with data**: See [Working with Beans](./documentation/10-working-with-beans.md)
- **For custom fields**: See [Field System](./documentation/09-fields.md)

### Next Steps

New to MintHCM frontend? Start here:

1. 📖 Read [Getting Started](./documentation/01-getting-started.md) to set up your environment
2. 🏗️ Understand [Project Architecture](./documentation/02-architecture.md)
3. 📁 Learn [Directory Structure](./documentation/03-directory-structure.md)
4. 🔨 See [Building & Deployment](./documentation/04-building-deployment.md) for production builds

Already familiar? Jump to:

- [Working with Beans](./documentation/10-working-with-beans.md) to learn CRUD operations
- [Field System](./documentation/09-fields.md) to create custom fields
- [Customization Guide](./documentation/11-customization.md) to extend functionality  