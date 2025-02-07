<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "./AuthenticatedLayout.vue";
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template v-if="$slots.header" #header>
      <slot name="header"></slot>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xs sm:rounded-lg">
          <slot></slot>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
