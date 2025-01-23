<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import RouletteGrid from "../Roulette/RouletteGrid.vue";

const { betsClosed } = defineProps<{
  betsClosed: boolean;
  errors?: {
    balance: string;
  };
}>();

const form = useForm({
  amount: undefined,
  position: undefined,
});

type Errors = typeof form.errors;
type ExtendedErrors = Errors & { balance?: string };

const errors = computed(() => form.errors as ExtendedErrors);

function submit() {
  form.submit("post", route("bet.store"), {
    preserveScroll: true,
  });

  form.reset();
}

const disabled = computed(() => betsClosed || form.processing);
</script>

<template>
  <form @submit.prevent="submit">
    <div class="flex flex-col gap-4">
      <div class="">
        <RouletteGrid v-model="form.position" />
        <span v-if="errors.position" class="pt-2 text-sm text-red-500">
          {{ errors.position }}
        </span>
      </div>
      <div class="flex gap-4">
        <div class="flex items-center gap-4">
          <label for="amount">Amount:</label>
          <input
            v-model="form.amount"
            class="rounded-lg"
            type="number"
            name="amount"
            id="amount"
            min="0"
            max="1000"
            required
          />
          <span v-if="errors.amount" class="pt-2 text-sm text-red-500">
            {{ errors.amount }}
          </span>
          <span v-if="errors.balance" class="pt-2 text-sm text-red-500">
            {{ errors.balance }}
          </span>
        </div>
        <button
          type="submit"
          class="max-w-64 rounded bg-blue-600 px-4 py-2 text-white"
          :class="[disabled ? 'cursor-not-allowed' : 'hover:bg-blue-500']"
          :disabled="disabled"
        >
          Place Bet
        </button>
      </div>
    </div>
  </form>
</template>
