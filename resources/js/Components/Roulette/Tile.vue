<script setup lang="ts">
import { isRed } from "@/utils/roulette";

defineProps<{ number: number }>();
</script>

<template>
  <div
    class="flex size-9 items-center justify-center border-2 text-center text-white"
    :class="number === 0 ? 'bg-green-500' : isRed(number) ? 'bg-red-500' : 'bg-black'">
    {{ number }}
  </div>
</template>
