<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import PrimaryButton from "../PrimaryButton.vue";
import RouletteGrid from "../Roulette/RouletteGrid.vue";

const { betsClosed } = defineProps<{
  betsClosed: boolean;
}>();

const form = useForm({
  amount: 1,
  position: undefined,
});

// Extend form errors with 'balance' from `BetController`
type ExtendedErrors = typeof form.errors & { balance?: string };
const errors = computed(() => form.errors as ExtendedErrors);

function submit() {
  form.submit("post", route("bet.store"), {
    preserveScroll: true,
  });

  setTimeout(() => form.clearErrors(), 10000);
}

const disabled = computed(() => betsClosed || form.processing);
</script>

<template>
  <div class="h-6">
    <p>{{ betsClosed ? "BETS CLOSED" : "BETS OPEN" }}</p>
  </div>
  <form @submit.prevent="submit">
    <div class="flex flex-col gap-4">
      <div>
        <div class="h-6">
          <span v-show="errors.position" class="text-sm text-red-500">
            Please select a number on the grid below.
          </span>
        </div>
        <RouletteGrid v-model="form.position" />
      </div>
      <div class="flex gap-4">
        <p class="flex items-center">Balance: {{ $page.props.auth.user.wallet.balance }}</p>
        <div class="ml-auto flex items-center gap-4">
          <label for="amount">Amount:</label>
          <input
            id="amount"
            v-model="form.amount"
            class="rounded-lg"
            type="number"
            name="amount"
            placeholder="1"
            min="1"
            max="1000"
            required />
        </div>
        <PrimaryButton type="submit" :disabled>Place Bet</PrimaryButton>
      </div>
      <div class="flex flex-col">
        <span v-if="errors.amount" class="pt-2 text-sm text-red-500">
          {{ errors.amount }}
        </span>
        <span v-if="errors.balance" class="pt-2 text-sm text-red-500">
          {{ errors.balance }}
        </span>
      </div>
    </div>
  </form>
</template>
