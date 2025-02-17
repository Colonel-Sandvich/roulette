<script setup lang="ts">
import { isRed, numbers, spinDownTimeInSeconds, spins } from "@/utils/roulette";
import { sleep } from "@/utils/sleep";
import { computed, ref, watch } from "vue";

const { spinTo } = defineProps<{
  spinTo: number | undefined;
}>();

const spinToIndex = computed(() => (spinTo !== undefined ? numbers.indexOf(spinTo) : undefined));

const showBall = ref(false);

// Hide ball for 500ms between games
watch(
  spinToIndex,
  async () => {
    if (spinToIndex.value !== undefined) {
      return;
    }

    showBall.value = false;

    await sleep(500);

    showBall.value = true;
  },
  {
    // Running immediately happens to fix a firefox bug relating to
    // @starting-style not being respected
    immediate: true,
  },
);
</script>

<template>
  <div class="text-white select-none">
    <div
      style="animation-duration: 24s"
      class="plate relative m-3 size-[350px] animate-spin rounded-full">
      <div
        class="absolute inset-[12%] z-1 block rounded-full border-stone-300 bg-black/65 inset-ring-3
          inset-ring-zinc-500"></div>
      <ul id="inner" class="relative block size-[350px]">
        <p
          v-if="showBall"
          :data-spinto="spinToIndex"
          class="ball absolute z-10 block rounded-full text-6xl leading-normal">
          {{ "\u2022" }}
        </p>
        <li
          v-for="(number, i) of numbers"
          :key="number"
          :style="`transform: rotate(${i * (360 / 37)}deg)`"
          style="left: calc(50% - 16px); border-top-width: 175px"
          class="absolute top-0 box-border inline-block h-4 w-8 origin-bottom border-x-[16px]
            border-x-transparent"
          :class="[
            number === 0
              ? 'border-t-green-500'
              : isRed(number)
                ? 'border-t-red-500'
                : 'border-t-black',
          ]">
          <span
            class="absolute -top-[175px] -left-[17px] inline-block w-8 scale-y-180 pt-3 text-center
              text-xl leading-none">
            {{ number }}
          </span>
        </li>
        <div
          class="absolute inset-[24%] z-3 block rounded-full border-3 border-zinc-500
            bg-neutral-600"></div>
      </ul>
      <div
        class="absolute -inset-1.5 block rounded-full border-6 border-[gold] inset-ring-2 ring-2
          ring-yellow-200 inset-ring-black"></div>
    </div>
  </div>
</template>

<style scoped>
/* Ball rotation */
@property --a {
  syntax: "<angle>";
  inherits: false;
  initial-value: -1000turn;
}
/* -1000turn / 1000s = -1turn/s constant rotation */
.ball {
  rotate: calc(mod(var(--a), 1turn));
  transition: --a 1000s linear;
  inset: 17%;
  @starting-style {
    --a: 0turn;
  }
}
.ball[data-spinto] {
  --a: 0deg;
  rotate: calc(-1turn * v-bind(spins) + v-bind(spinToIndex) * 1turn / 37 + 59deg);
  inset: 22%;
  /* ~<spins> turns before stopping.
   Don't know why but we have to add an extra 59deg  */
  transition:
    rotate calc(v-bind(spinDownTimeInSeconds) * 1s) ease-out,
    inset 2s ease-out;
}
</style>
