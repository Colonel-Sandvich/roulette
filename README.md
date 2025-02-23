# Larasino (A fake casino powered by Laravel)

## What this website does

After a user registers an account they can view a roulette wheel and place bets on the result. The betting is simplified, only allowing a single number to be bet on rather than the typical red / black, 1-12 etc. Each game lasts 60 seconds, beginning and ending on the start of each minute. On the dashboard the user can increase their wallet balance by 1000. The database resets every day at midnight to avoid hitting storage limits.

## Why a Casino

I set out to refine my Laravel skills while experimenting with [Server-sent Events (SSE)](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events).
So I needed a project idea that would best utilize real-time data.
An online casino seemed to fit the bill, as it required a long running schedule/cron to manage roulette games and real-time updates for the user.

## Successes

- I implemented SSE via Redis' Pub/Sub system, reducing the need for polling and minimising server strain.

- User's bets are guaranteed to never allow their wallet balance to go below 0. Bets placed and updates to the balance are done in transactions and the decrement operation is atomic. This combined with a database column check trigger prevents the user from double betting and getting a negative balance.

- I wrote some SQL queries that I'm quite proud of. [This one](https://github.com/Colonel-Sandvich/roulette/blob/master/app/Jobs/ProcessBets.php#L46) took a while to get right but it updates all winning user's wallets with the correct amount in a single query. If restricted to eloquent, then this would likely require a separate query per winning user.

- I attempted to colocate code by domain rather than by type. So all things `Bet` related go in one folder rather than having a monolithic `Services` folder that contains many unrelated domains.
  But honestly, this project is too small to really determine if this was a benefit or not.

## Learning moments

- Since the roulette game timing is entirely predictable, polling at the end of each minute until the latest update appears would likely be more practical. There is a non-zero overhead of keeping these long lived connections open AND since we're using inertia it's easier to ask the server for the new data anyway.

- The details surrounding SQLite's transaction behaviour and read guarantees.

- How to setup and use ESLint.

## Limitations and Frustrations

- I'm using Railway as a hosting provider (because it's free). Unfortunately, they have a hard cap of 5 seconds on all non-websocket connections and I only found this out very late into the project.
  In prod we just fallback to polling.

- Inertiajs completely reworks how api requests are done and needs a change of mental model.

    - It ends up leaking into testing code.
    - It heavily depends on stateful sessions to be able to redirect back to the user's previous url.
        - The form submission page leaks into backend logic. [(See here)](https://github.com/Colonel-Sandvich/roulette/blob/master/app/Http/Controllers/WalletController.php#L22)
        - This caused many hours of frustration when trying to implement SSE as a faulty bet submission would redirect the user to the SSE stream repeatedly instead of the roulette page.

- SQLite doesn't allow for adding most constraints after table creation. LibSQL might be better here.

    - This led to slightly uglier code as I had to do manual SQL instead of Laravel's typical migration functions.

- PHP's code execution flow is simple but lacks useful things provided by asynchronous programming. Normally, this wouldn't be an issue, but it creates problems for the [SSE endpoint](https://github.com/Colonel-Sandvich/roulette/blob/master/app/Http/Controllers/RouletteController.php#L70-L82). Since `Redis::subscribe` is blocking we actually have no idea the user has closed the connection until the next event comes in and we try to flush new data. So HTTP connections stick around for longer than needed. This isn't necessarily a problem with SSE but the tooling around websockets is so much better that it would have fixed this problem here at the cost of increasing the app's complexity.

## Future Improvments

- Add more tests.
- Add CI/CD to run the various `composer/bun format/lint` scripts and the test suite.
- Swap SQLite to LibSQL.
- Dockerise it all so I don't have to connect to the production Redis instance.
    - With a LibSQL container I could split the queue worker and cron worker into seperate containers for better separation. Right now they have to live alongside the main server as it has the database mounted to it.
- Move off Railway to a proper VPS.

## Tech Stack

- Laravel (PHP)
- TypeScript (JavaScript)
- Vuejs
- Inertiajs
- Tailwindcss
- SQLite
- Redis
- Pest (PHPUnit)
- ESLint
- Larastan (PHPStan)
- Prettier
- Pint

## Demo

![Demo Video](/Screencast%20From%202025-02-23%2014-02-57.mp4)
