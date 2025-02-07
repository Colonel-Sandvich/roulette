<script setup lang="ts">
import { index } from "@/utils/roulette";
import Tile from "./Tile.vue";

const selected = defineModel<number | null>();

function select(num: number) {
  if (selected.value === num) {
    selected.value = null;
    return;
  }

  selected.value = num;
}

const reorderedNumbers = Array.from({ length: 36 }, (_, i) => index(i + 1));
</script>

<template>
  <div class="rounded-xl bg-green-700 p-4 text-white outline-white">
    <div class="grid cursor-pointer grid-flow-row grid-cols-13 grid-rows-3 select-none">
      <div
        class="row-span-3 flex w-9 items-center justify-center border-2"
        :class="0 === selected ? 'bg-white text-black' : 'bg-green-500'"
        @click="select(0)">
        <div>0</div>
      </div>
      <Tile
        v-for="number of reorderedNumbers"
        :key="number"
        v-memo="[number === selected]"
        :class="{ 'z-10 bg-white! text-black!': number === selected }"
        :number
        @click="select(number)" />
    </div>
  </div>
</template>
