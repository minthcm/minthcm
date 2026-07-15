# MCP Apps

Directory for MCP Apps (https://modelcontextprotocol.io/extensions/apps/overview). Each app is built into a single HTML file and served from `mcp/apps/dist/`.

## Layout

- **One app per directory.** Each directory must contain:
  - `{filename}.html` – entry page (script tag should point to script inside `src/` dir).
  - `src/` – TypeScript and CSS (e.g. `main.ts`, `style.css`).

- **Build output:** All apps are built into `mcp/apps/dist/` as `{app_name}.html` (e.g. `search-records` -> `dist/search-records.html`).

## Adding a new app

1. Create a new directory under `mcp/apps/`, e.g. `mcp/apps/my-tool/`.
2. Add html file that loads your entry script (e.g. `<script type="module" src="./src/main.ts"></script>`).
3. Add `src/main.ts` (and optionally `src/style.css`). Use `@modelcontextprotocol/ext-apps` `App` for host communication.
4. During development, run `npm run "dev mcp-apps"` from `mcp/apps/` and open `http://localhost:5173/my-tool/my-tool.html` for HMR. Unfortunately works only when you want to check styling because browsers don't support MCP protocol, so the data flow won't work. For testing your tools use MCP clients like Postman.
5. Build with `npm run "build mcp-apps"` from `mcp/apps/`. Output will be `mcp/apps/dist/my-tool.html`.
6. Create a resource class under `mcp/Capabilities/Resources/` extending `AbstractMCPAppResource` and expose it with `#[McpResource]`. Load file content with `self::appDistPath('my-tool')`.
7. In your tool class, link the app via `#[McpTool(meta: ['ui' => ['resourceUri' => MyToolResource::URI, 'visibility' => ['model', 'app']]])]`. Use the URI convention `ui://mint-mcp/<tool-name>`.

## Commands

From `mcp/apps/`:

- `npm install` – install dependencies
- `npm run "dev mcp-apps"` – run Vite dev server with HMR for all apps
- `npm run "build mcp-apps"` – typecheck, build all apps, and flatten output to `dist/{name}.html`
