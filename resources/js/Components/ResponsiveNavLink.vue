<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineProps<{
  href: string;
  active?: boolean;
}>();
</script>

<template>
  <Link
    :href="href"
    class="block w-full border-l-4 py-2 ps-3 pe-4 text-start text-base font-medium transition
      duration-150 ease-in-out focus:outline-hidden"
    :class="
      active
        ? `border-indigo-400 bg-indigo-50 text-indigo-700 focus:border-indigo-700
          focus:bg-indigo-100 focus:text-indigo-800`
        : `border-transparent text-gray-600 hover:border-gray-300 hover:bg-gray-50
          hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800`
    ">
    <slot></slot>
  </Link>
</template>
