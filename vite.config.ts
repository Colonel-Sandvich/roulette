import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import MarkdownIt from "markdown-it";
import { defineConfig, type Plugin } from "vite";

const markdown = new MarkdownIt({ html: true, linkify: true });

function markdownToHtml(): Plugin {
  return {
    name: "markdown-to-html",
    transform(code, id) {
      if (!id.endsWith(".md")) {
        return null;
      }

      return {
        code: `export const html = ${JSON.stringify(markdown.render(code))};`,
        map: null,
      };
    },
  };
}

export default defineConfig({
  plugins: [
    laravel({
      input: "resources/js/app.ts",
      ssr: "resources/js/ssr.ts",
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    tailwindcss(),
    markdownToHtml(),
  ],
  envPrefix: ["VITE_", "ROULETTE_"],
});
