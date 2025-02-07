import js from "@eslint/js";
import skipFormattingConfig from "@vue/eslint-config-prettier/skip-formatting";
import { defineConfigWithVueTs, vueTsConfigs } from "@vue/eslint-config-typescript";
import pluginVue from "eslint-plugin-vue";

export default defineConfigWithVueTs(
  skipFormattingConfig,
  js.configs.recommended,
  pluginVue.configs["flat/recommended"],
  vueTsConfigs.recommendedTypeChecked,
  {
    files: ["resources/js/**/*.{vue,ts,js}"],
    ignores: ["resources/js/types/global.d.ts"],
    rules: {
      "vue/multi-word-component-names": "off",
      "no-undef": "off",
      "vue/max-attributes-per-line": [
        "warn",
        {
          singleline: {
            // Prettier will chop down attributes if line too long
            max: 1000,
          },
          multiline: {
            max: 1,
          },
        },
      ],
      "vue/singleline-html-element-content-newline": "off",
      "vue/html-closing-bracket-newline": "off",
      "vue/html-self-closing": [
        "warn",
        {
          html: {
            void: "always",
            normal: "never",
            component: "any",
          },
        },
      ],
      "vue/attribute-hyphenation": "off",
    },
    linterOptions: {
      // noInlineConfig: true,
      reportUnusedDisableDirectives: "error",
    },
  },
  {
    ignores: ["vendor/", "public/", "bootstrap/", "resources/js/ziggy.js"],
  },
);
