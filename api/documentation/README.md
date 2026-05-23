# MintHCM API Documentation

Welcome to the MintHCM API documentation. This guide will help you understand the architecture, patterns, and best practices for working with the MintHCM API layer.

## Table of Contents

1. [Getting Started](./01-getting-started.md) - Quick setup and first steps
2. [Directory Structure](./02-directory-structure.md) - Understanding the folder organization
3. [Architecture & Design Patterns](./03-architecture.md) - Core architectural concepts
4. [Configuration vs Constants](./04-config-vs-constants.md) - Understanding the difference
5. [Routing System](./05-routing.md) - How to add and manage routes
6. [Database Communication](./06-database.md) - Working with Doctrine ORM
7. [Legacy Integration](./07-legacy-integration.md) - Connecting to the legacy codebase
8. [Extending the API](./08-extending-api.md) - Using the custom directory
9. [Controllers & Actions](./09-controllers.md) - Building request handlers
10. [Entities & Repositories](./10-entities-repositories.md) - Data layer patterns
11. [Middlewares](./11-middlewares.md) - Request/response processing
12. [Testing](./12-testing.md) - Writing and running tests
13. [MintLogic](./13-mintlogic.md) - Dynamic form logic and validation

## Overview

The MintHCM API is a modern REST API layer built on top of the MintHCM legacy system. It uses:

- **Slim Framework** - Lightweight PHP micro-framework for routing and HTTP handling
- **Doctrine ORM** - Object-relational mapping for database operations
- **PSR-7** - HTTP message interfaces
- **PHP-DI** - Dependency injection container

The API provides a clean, modern interface for client applications while maintaining backward compatibility with the legacy SuiteCRM/SugarCRM codebase.

## Quick Links

- **Add a new route**: See [Routing System](./05-routing.md)
- **Extend existing functionality**: See [Extending the API](./08-extending-api.md)
- **Connect to legacy code**: See [Legacy Integration](./07-legacy-integration.md)
- **Work with database**: See [Database Communication](./06-database.md)
- **Define form logic and validation**: See [MintLogic](./13-mintlogic.md)

## Project Philosophy

The API layer follows these principles:

1. **Separation of Concerns** - Clear boundaries between routing, business logic, and data access
2. **Extensibility** - Everything can be extended via the `custom/` directory
3. **Legacy Compatibility** - Seamless integration with existing MintHCM/SuiteCRM code
4. **Modern Standards** - PSR compliance, Composer dependencies, namespaced code
5. **Developer Experience** - Clear conventions, dependency injection, and comprehensive tooling

## Support

For issues and questions, please refer to the main MintHCM repository or documentation.
