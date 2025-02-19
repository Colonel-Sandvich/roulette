<script setup lang="ts">
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Layout from "@/Layouts/Layout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { onUnmounted } from "vue";

function addToBalance() {
  router.post(route("wallet.update_balance"));
}

const source = new EventSource(route("roulette.test-stream"));

source.onmessage = () => {
  console.log("Message receieved!");
};

onUnmounted(() => source.close());
</script>

<template>
  <Head title="Dashboard" />

  <Layout>
    <template #header>
      <h2 class="text-xl leading-tight font-semibold text-gray-800">Dashboard - Wallet</h2>
    </template>

    <div class="flex flex-col gap-40 p-6 text-gray-900">
      <div class="flex justify-center gap-8 px-10 py-6">
        <p class="flex items-center">Balance: {{ $page.props.auth.user.wallet.balance }}</p>
        <PrimaryButton @click="addToBalance">Add 1000 to my balance</PrimaryButton>
      </div>
      <Link :href="route('roulette.index')" class="flex justify-center">
        <div
          class="inline-flex cursor-pointer items-center rounded-lg bg-green-700 px-8 py-6 text-lg
            font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out
            focus:bg-green-400">
          Head to the table
        </div>
      </Link>
    </div>
  </Layout>
</template>
