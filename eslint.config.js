import js from "@eslint/js";
import skipFormattingConfig from "@vue/eslint-config-prettier/skip-formatting";
import {
  defineConfig,
  createConfig as vueTsEslintConfig,
} from "@vue/eslint-config-typescript";
import pluginVue from "eslint-plugin-vue";

export default defineConfig(
  js.configs.recommended,
  ...pluginVue.configs["flat/recommended"],
  skipFormattingConfig,
  vueTsEslintConfig(),
  {
    files: ["resources/js/**/*.{vue,ts,js}"],
    ignores: ["resources/js/types/global.d.ts"],
    rules: {
      "vue/multi-word-component-names": "off",
      "no-undef": "off",
    },
    linterOptions: {
      // noInlineConfig: true,
      reportUnusedDisableDirectives: "error",
    },
  },
  {
    ignores: ["vendor/", "public/", "bootstrap/"],
  },
);
