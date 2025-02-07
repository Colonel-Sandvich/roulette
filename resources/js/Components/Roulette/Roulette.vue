<script setup lang="ts">
import { Bet, ClosedGame } from "@/types/roulette";
import { router, usePoll } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import PlaceBetForm from "../Bet/PlaceBetForm.vue";
import Bets from "./Bets.vue";
import GamesList from "./GamesList.vue";
import Wheel from "./Wheel.vue";

const { previousGames, bets } = defineProps<{
  previousGames: Array<ClosedGame>;
  bets: Array<Bet>;
}>();

const currentGame = computed(() => previousGames[0]!);

const result = computed(() => (betsClosed.value ? currentGame.value.result : null));

const previousGamesView = computed(() =>
  previousGames.filter((game) => {
    // If bets are open show all games
    if (!betsClosed.value) {
      return true;
    }

    // Otherwise filter out most recent result while the ball is still spinning
    return game.id !== currentGame.value.id;
  }),
);

const betsClosed = ref(false);

// New game has just finished
// Close bets and show the result for 10 seconds
// Reload including bets after
watch(
  () => currentGame.value.id,
  () => {
    betsClosed.value = true;

    setTimeout(() => {
      betsClosed.value = false;
      router.reload();
    }, 10000);
  },
);

usePoll(2000, {
  // Don't need to refresh bets every 2 seconds, they update on submission
  except: ["bets"],
});
</script>

<template>
  <div class="flex gap-8 px-10 py-6">
    <GamesList :games="previousGamesView" />

    <div class="flex grow flex-col items-center gap-4">
      <Wheel :spinTo="result" />
      <PlaceBetForm :betsClosed />
    </div>

    <Bets :bets :result />
  </div>
</template>
