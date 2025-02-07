<script setup lang="ts">
import Roulette from "@/Components/Roulette/Roulette.vue";
import Layout from "@/Layouts/Layout.vue";
import { Bet, ClosedGame } from "@/types/roulette";
import { Head } from "@inertiajs/vue3";

const props = defineProps<{
  previousGames: Array<ClosedGame>;
  bets: Array<Bet>;
}>();
</script>

<template>
  <Head title="Roulette" />

  <Layout>
    <Roulette v-bind="props" />
  </Layout>
</template>
