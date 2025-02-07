<script setup lang="ts">
import { ClosedGame } from "@/types/roulette";
import Tile from "./Tile.vue";

defineProps<{
  games: Array<ClosedGame>;
}>();

// Only show the first `limit` (default 20) results to avoid layout changes
// nth-[n+21]:hidden
const limit = +import.meta.env.ROULETTE_PREVIOUS_GAMES_DISPLAY_COUNT;
</script>

<template>
  <div class="flex basis-1/10 flex-col items-center gap-1">
    <h3 class="text-center text-xl">Previous Games</h3>
    <div class="flex flex-col rounded-md bg-green-700 px-8 py-4">
      <Tile
        v-for="game of games"
        :key="game.id"
        :number="game.result"
        :class="`nth-[n+${limit + 1}]:hidden`" />
    </div>
  </div>
</template>
