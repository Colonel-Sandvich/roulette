import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";
import { plugin as markdownPlugin, Mode } from "vite-plugin-markdown";
import vueDevTools from "vite-plugin-vue-devtools";

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
    vueDevTools({
      appendTo: "app.ts",
      launchEditor: "codium",
    }),
    tailwindcss(),
    markdownPlugin({
      mode: [Mode.VUE],
    }),
  ],
  envPrefix: ["VITE_", "ROULETTE_"],
});
