<script setup lang="ts">
import PlaceBetForm from "@/Components/Bet/PlaceBetForm.vue";
import Wheel from "@/Components/Roulette/Wheel.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePoll } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
type OpenGame = {
  id: number;
  result: null;
  created_at: Date;
};

type ClosedGame = {
  id: number;
  result: number;
  created_at: Date;
};

type Bet = {
  position: number;
  amount: number;
};

const { lastGame, games } = defineProps<{
  currentGame: OpenGame;
  lastGame: ClosedGame;
  bets: Array<Bet>;
  games: Array<ClosedGame>;
}>();

const lastGames = computed(() =>
  games.filter((game) => game.id !== lastGame.id),
);

const gameResult = ref<number | null>(null);

watch(
  () => lastGame.id,
  () => {
    gameResult.value = lastGame.result;

    setTimeout(() => (gameResult.value = null), 10000);
  },
);

usePoll(5000);
// Place your bets
// Bets are closing
// Bets closed
// Ball spin down animation
// Ball stops
// Show result
// Place your bets...
</script>

<template>
  <Head title="Roulette" />

  <AuthenticatedLayout>
    <template #header>
      <h1
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        Roulette
      </h1>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
        >
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-pink-500">WHEEL!</h2>
            <Wheel :spinTo="gameResult" />
          </div>
          <div class="flex gap-8 p-8">
            <PlaceBetForm :betsClosed="false" />
            <div class="flex flex-col">
              <h3 class="text-xl">My Bets</h3>
              <div v-for="bet of bets">
                {{ bet.amount }} on {{ bet.position }}
              </div>
            </div>
            <div class="flex flex-col">
              <h3 class="text-xl">Previous Games</h3>
              <div v-for="lastGame of lastGames">
                {{ lastGame.result }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
