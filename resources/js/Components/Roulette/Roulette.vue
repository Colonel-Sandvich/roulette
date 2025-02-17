<script setup lang="ts">
import { Bet, ClosedGame } from "@/types/roulette";
import { router } from "@inertiajs/vue3";
import { computed, onUnmounted, ref, watch } from "vue";
import PlaceBetForm from "../Bet/PlaceBetForm.vue";
import Bets from "./Bets.vue";
import GamesList from "./GamesList.vue";
import Wheel from "./Wheel.vue";

const { previousGames, bets } = defineProps<{
  previousGames: Array<ClosedGame>;
  bets: Array<Bet>;
}>();

const currentGame = ref<ClosedGame>();

const result = computed(() => currentGame.value?.result);

// New game has just finished
// Close bets and show the result for 10 seconds
// Reload including bets after
watch(
  () => previousGames[0]?.id,
  () => {
    currentGame.value = previousGames[0];

    setTimeout(() => {
      currentGame.value = undefined;
      router.reload();
    }, 10 * 1000);
  },
);

// TODO: Refactor SSE and polling as fallback into composable
// const { start, stop } = usePoll(
//   2000,
//   {
//     // Don't need to refresh bets every 2 seconds, they update on submission
//     except: ["bets"],
//   },
//   {
//     autoStart: false,
//   },
// );

const source = new EventSource(route("roulette.stream"));

source.addEventListener("game_finished", function () {
  router.reload({
    except: ["bets"],
  });
});

onUnmounted(() => source.close());
</script>

<template>
  <div class="flex gap-8 px-10 py-6">
    <GamesList :games="previousGames" :currentGame />

    <div class="flex grow flex-col items-center gap-4">
      <Wheel :spinTo="result" />
      <PlaceBetForm :betsClosed="result !== undefined" />
    </div>

    <Bets :bets :result />
  </div>
</template>
