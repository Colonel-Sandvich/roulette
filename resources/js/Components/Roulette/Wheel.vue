<script setup lang="ts">
import { isRed, numbers } from "@/utils/roulette";
import { sleep } from "@/utils/sleep";
import { computed, ref, watch } from "vue";

const { spinTo } = defineProps<{
  spinTo: number | null;
}>();

const spinToIndex = computed(() =>
  spinTo !== null ? numbers.indexOf(spinTo) : null,
);

const spins = 2;

const showBall = ref(false);

// Hide ball for 500ms between games
watch(
  spinToIndex,
  async () => {
    if (spinToIndex.value !== null) {
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
  <div class="text-white">
    <div class="plate" id="plate">
      <ul class="inner" id="inner">
        <p
          v-if="showBall"
          :data-spinto="spinToIndex"
          class="ball absolute inset-[22%] z-10 block rounded-full text-6xl leading-normal"
        >
          {{ "\u2022" }}
        </p>
        <li
          v-for="(number, i) of numbers"
          :style="`transform: rotate(${i * (360 / 37)}deg)`"
          style="left: calc(50% - 16px); border-top-width: 175px"
          class="absolute top-0 box-border inline-block h-4 w-8 origin-bottom border-x-[16px] border-x-transparent"
          :class="[
            number === 0
              ? 'border-t-green-500'
              : isRed(number)
                ? 'border-t-red-500'
                : 'border-t-black',
          ]"
        >
          <span class="pit text-center text-xl leading-none">{{ number }}</span>
        </li>
      </ul>
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
.ball {
  rotate: calc(mod(var(--a), 1turn));
  transition: --a 1000s linear;
  @starting-style {
    --a: 0turn;
  }
}
.ball[data-spinto] {
  --a: 0deg;
  transition: --a 0.8s;
  rotate: calc(
    -1turn * v-bind(spins) + v-bind(spinToIndex) * 1turn / 37 + 60deg
  );
  /* ~<spins> turns before stopping.
   Don't know why but we have to add an extra 60deg  */
  transition: rotate 4s ease-out;
}

@keyframes spin {
  to {
    rotate: 1turn;
  }
}

.plate {
  background-color: gray;
  width: 350px;
  height: 350px;
  margin: 12px;
  border-radius: 50%;
  position: relative;
  animation: spin 24s infinite linear;
}

.plate:after,
.plate:before {
  content: "";
  display: block;
  position: absolute;
  border-radius: 50%;
}
.plate:after {
  inset: -6px;
  border: 6px solid gold;
  box-shadow:
    inset 0px 0px 0px 2px #b39700,
    0px 0px 0px 2px #ffeb80;
}
.plate:before {
  background: rgba(0, 0, 0, 0.65);
  border: 1px solid silver;
  box-shadow: inset 0px 0px 0px 2px #808080;
  inset: 12%;
  z-index: 1;
}
.pit {
  color: #fff;
  padding-top: 12px;
  width: 32px;
  display: inline-block;
  transform: scale(1, 1.8);
  position: absolute;
  top: -175px;
  left: -17px;
}
.inner {
  display: block;
  height: 350px;
  width: 350px;
  position: relative;
}

.inner:after {
  content: "";
  display: block;
  position: absolute;
  border-radius: 50%;
  z-index: 3;
  inset: 24%;
  background-color: #4d4d4d;
  border: 3px solid #808080;
}
</style>
