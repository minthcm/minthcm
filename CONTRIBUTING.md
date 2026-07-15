# Contributing to MintHCM

Thank you for your interest in contributing to MintHCM — the open, agent-ready HCM platform built for the agentic AI era.

MintHCM is an open-source project (AGPL v3) and we welcome contributions from developers, HR practitioners, integrators, and anyone who shares our values: **openness, data sovereignty, and AI-readiness without vendor lock-in**.

---

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Ways to Contribute](#ways-to-contribute)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)
- [Contributing Code](#contributing-code)
- [Development Setup](#development-setup)
- [Pull Request Guidelines](#pull-request-guidelines)
- [Commit Message Convention](#commit-message-convention)
- [Code Style](#code-style)
- [Community & Support](#community--support)

---

## Code of Conduct

We are committed to providing a welcoming and respectful environment for everyone.
Please be kind, constructive, and professional in all interactions — in issues, pull requests, and discussions.

---

## Ways to Contribute

You don't have to write code to contribute! Here's how you can help:

- 🐛 **Report bugs** — use our [Bug Report template](.github/ISSUE_TEMPLATE/bug_report.yml)
- 💡 **Suggest features** — use our [Feature Request template](.github/ISSUE_TEMPLATE/feature_request.yml)
- 📖 **Improve documentation** — fix typos, clarify instructions, add examples
- 🌍 **Translate** — help localize MintHCM for your region
- 🧪 **Test** — try out new releases and report what you find
- 💬 **Help others** — answer questions in [Discussions](https://github.com/minthcm/minthcm/discussions)
- 🔁 **Review pull requests** — share your expertise on open PRs

---

## Reporting Bugs

Before reporting a bug:
1. Check if it's already reported in [Issues](https://github.com/minthcm/minthcm/issues)
2. Make sure you're using a [supported version](https://github.com/minthcm/minthcm/releases)

To report a bug, use the **[Bug Report form](https://github.com/minthcm/minthcm/issues/new?template=bug_report.yml)**.

Please include: what happened, steps to reproduce, expected behavior, and your MintHCM version.

---

## Suggesting Features

To suggest a new feature, use the **[Feature Request form](https://github.com/minthcm/minthcm/issues/new?template=feature_request.yml)**.

Good feature requests describe:
- The problem or limitation you're experiencing
- What you'd like to happen
- Who would benefit

We especially welcome ideas related to our strategic focus areas:
- AI agent ecosystem (MCP, A2A, WebMCP integrations)
- Data sovereignty and regional compliance (GDPR, APPI, PDPA, CCPA, etc.)
- Framework-level customization and extensibility
- Mobile and accessibility improvements

---

## Contributing Code

### Before You Start

For **small fixes** (typos, minor bugs): go ahead and open a PR directly.

For **larger changes** (new features, architectural changes, new modules): please open an issue first to discuss your idea. This avoids wasted effort and ensures alignment with the project roadmap.

### Fork & Branch

```bash
# 1. Fork the repository on GitHub

# 2. Clone your fork
git clone https://github.com/YOUR_USERNAME/minthcm.git
cd minthcm

# 3. Create a feature branch
git checkout -b feature/your-feature-name
# or for bug fixes:
git checkout -b fix/issue-123-short-description
```

### Branch Naming

| Type | Pattern | Example |
|------|---------|---------|
| Feature | `feature/short-description` | `feature/mcp-pagination` |
| Bug fix | `fix/issue-NNN-description` | `fix/issue-42-leave-date-clear` |
| Documentation | `docs/what-changed` | `docs/update-oauth2-setup` |
| Refactor | `refactor/what-changed` | `refactor/recordview-composables` |

---

## Development Setup

### System Requirements

| Component | Version |
|-----------|---------|
| PHP | 8.2 |
| MySQL | 8.0 / MariaDB 10.5–10.11 |
| Elasticsearch | 7.10–7.16 |
| Node.js | 21 |

### Quick Start

```bash
# Install PHP dependencies
composer install

# Install frontend dependencies and build
cd vue
npm install
npm run build
cd ..

# Generate OAuth2 keys (required)
./MintCLI oauth2:create-keys
./MintCLI oauth2:regenerateClientSecret

# Rebuild the instance
./MintCLI instance:rebuild
```

For full installation instructions, see the [README](README.md) and [release notes](https://github.com/minthcm/minthcm/releases).

---

## Pull Request Guidelines

- **One PR = one change**: keep your PR focused on a single bug fix or feature
- **Link to an issue**: reference the related issue with `Closes #NNN` or `Refs #NNN`
- **Fill in the PR template**: it helps reviewers understand your changes quickly
- **Write or update tests** where applicable
- **Update documentation** if your change affects user-facing behavior
- **Keep your branch up to date** with `master` before requesting review

---

## Commit Message Convention

We follow a simple convention based on [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>: <short description>

[optional body]

[optional footer: Closes #NNN]
```

**Types:**

| Type | Use for |
|------|---------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation only |
| `refactor` | Code change that neither fixes a bug nor adds a feature |
| `test` | Adding or fixing tests |
| `chore` | Maintenance tasks (deps, build, CI) |
| `style` | Formatting, missing semicolons, etc. |

**Examples:**
```
feat: add mass approve action to Leave Requests list view
fix: resolve date field clearing on RecordView save
docs: update OAuth2 setup instructions for 4.3 upgrade
```

---

## Code Style

### PHP (Backend / API)

- PSR-12 coding standard
- Type hints required for new code
- Use Doctrine entities for database operations (see `api/app/Entities/`)
- Place custom logic in `api/custom/` — never modify core files directly
- MintLogic definitions go in `api/custom/lib/MintLogic/{ModuleName}/`

### Vue.js (Frontend)

- Vue 3 Composition API with composables
- Vuetify 3 components for UI
- Follow existing patterns in `vue/src/views/` and `vue/src/composables/`
- Use TypeScript where existing code does

### General

- No commented-out code in PRs
- No `console.log` or `var_dump` left in submitted code
- English for all code, comments, and PR descriptions

---

## Community & Support

- 💬 **Discussions**: [github.com/minthcm/minthcm/discussions](https://github.com/minthcm/minthcm/discussions)
- 🌐 **Website**: [minthcm.org](https://minthcm.org)
- 📦 **Releases**: [github.com/minthcm/minthcm/releases](https://github.com/minthcm/minthcm/releases)

---

*MintHCM is built by a global community of contributors who believe HR software should be open, adaptable, and ready for the agentic AI era. We're glad you're here.*
