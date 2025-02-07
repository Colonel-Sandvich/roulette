<script setup lang="ts">
import { Bet } from "@/types/roulette";
import { spinDownTimeInSeconds } from "@/utils/roulette";
import Tile from "./Tile.vue";

defineProps<{
  bets: Array<Bet>;
  result: number | null;
}>();
</script>

<template>
  <div class="flex basis-1/8 flex-col items-center gap-1">
    <h3 class="text-xl">My Bets</h3>
    <div
      v-show="bets.length > 0"
      class="flex flex-col items-end gap-1 rounded-md bg-green-700 px-2 py-4 text-white">
      <div
        v-for="bet of bets"
        :key="bet.id"
        :style="`transition-delay: ${spinDownTimeInSeconds + 1}s`"
        class="flex items-center gap-2 rounded-lg p-1 transition"
        :class="{ 'ring-4 ring-yellow-500': bet.position === result }">
        <p>{{ bet.amount }} on</p>
        <Tile :number="bet.position" />
      </div>
    </div>
  </div>
</template>
