import path from "node:path";
import { readdirSync } from "node:fs";
import { defineConfig } from "vite";
import { viteSingleFile } from "vite-plugin-singlefile";

const appsDir = path.resolve(__dirname, ".");
const distDir = path.resolve(__dirname, "dist");

const appEntries = readdirSync(appsDir, { withFileTypes: true })
  .filter((d) => d.isDirectory() && !d.name.startsWith("."))
  .reduce<Record<string, string>>((acc, d) => {
    const dirPath = path.join(appsDir, d.name);
    const htmlFile = readdirSync(dirPath).find((f) => f.endsWith(".html"));
    if (htmlFile) {
      acc[d.name] = path.join(dirPath, htmlFile);
    }
    return acc;
  }, {});

if (Object.keys(appEntries).length === 0) {
  console.warn("No app directories with an .html file found in mcp/apps");
}

export default defineConfig(({ command }) => ({
  appType: "mpa",
  plugins: command === "build" ? [viteSingleFile()] : [],
  build: {
    outDir: distDir,
    emptyOutDir: false,
    cssMinify: true,
    minify: true,
    rollupOptions: {
      input: appEntries,
      output: {
        entryFileNames: "assets/[name].js",
        assetFileNames: "assets/[name].[ext]",
      },
    },
  },
}));
