<script setup lang="ts">
import { index, isRed } from "@/utils/roulette";

const selected = defineModel<number | null>();

function select(num: number) {
  if (selected.value === num) {
    selected.value = null;
    return;
  }

  selected.value = num;
}
</script>

<template>
  <div class="w-[30rem] bg-green-600 p-4 text-white outline-white">
    <div
      class="grid w-[28rem] cursor-pointer select-none grid-flow-row grid-cols-13 grid-rows-3"
    >
      <div
        class="row-span-3 flex w-9 items-center justify-center border-2"
        @click="select(0)"
        :class="0 === selected ? '!bg-white text-black' : ''"
      >
        <div>0</div>
      </div>
      <div
        v-for="i of 36"
        @click="select(index(i))"
        class="flex size-9 items-center justify-center border-2 text-center"
        :class="[
          isRed(index(i)) ? 'bg-red-500' : 'bg-black',
          index(i) === selected ? '!bg-white text-black' : '',
        ]"
      >
        {{ index(i) }}
      </div>
    </div>
  </div>
</template>
