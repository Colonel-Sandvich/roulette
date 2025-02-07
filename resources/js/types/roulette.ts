export type OpenGame = {
  id: number;
  result: null;
  created_at: Date;
};

export type ClosedGame = {
  id: number;
  result: number;
  created_at: Date;
};

export type Bet = {
  id: number;
  position: number;
  amount: number;
};
