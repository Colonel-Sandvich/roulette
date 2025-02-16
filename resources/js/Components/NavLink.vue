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
    class="inline-flex items-center border-b-2 px-1 pt-1 text-sm leading-5 font-medium transition
      duration-150 ease-in-out focus:outline-hidden"
    :class="
      active
        ? 'border-indigo-400 text-gray-900 focus:border-indigo-700'
        : `border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700
          focus:border-gray-300 focus:text-gray-700`
    ">
    <slot></slot>
  </Link>
</template>
