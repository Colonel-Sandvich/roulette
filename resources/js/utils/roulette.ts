export const numbers = [
  0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14,
  31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26,
];

const numRed = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];

export function isRed(x: number): boolean {
  return numRed.includes(x);
}

export function index(i: number) {
  i -= 1;
  const div = Math.floor(i / 12);
  const rem = i % 12;

  return (rem + 1) * 3 - div;
}

// Wheel animation consts
export const spins = 2;
export const spinDownTimeInSeconds = 4;
