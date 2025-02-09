<script setup lang="ts">
import AuthenticatedLayout from "./AuthenticatedLayout.vue";
</script>

<template>
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
