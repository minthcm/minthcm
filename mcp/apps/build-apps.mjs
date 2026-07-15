import { build } from 'vite';
import { viteSingleFile } from 'vite-plugin-singlefile';
import { readdirSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const distDir = path.resolve(__dirname, 'dist');

const apps = readdirSync(__dirname, { withFileTypes: true })
  .filter((d) => d.isDirectory() && !d.name.startsWith('.'))
  .flatMap((d) => {
    const appRoot = path.join(__dirname, d.name);
    const htmlFile = readdirSync(appRoot).find((f) => f.endsWith('.html'));
    return htmlFile ? [{ name: d.name, appRoot, htmlFile }] : [];
  });

if (apps.length === 0) {
  console.warn('No app directories with an .html file found in mcp/apps');
  process.exit(0);
}

for (const app of apps) {
  console.log(`Building ${app.name}...`);
  await build({
    root: app.appRoot,
    appType: 'spa',
    plugins: [viteSingleFile()],
    build: {
      outDir: distDir,
      emptyOutDir: false,
      cssMinify: true,
      minify: true,
      rollupOptions: {
        input: path.join(app.appRoot, app.htmlFile),
      },
    },
    logLevel: 'warn',
  });
}

console.log(`Built ${apps.length} app(s) to ${distDir}`);
