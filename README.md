# Larasino (A fake casino powered by Laravel)

## Why a Casino

I wanted to refine my Laravel skills while trying out [Server-Sent Events (SSE)](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events).  
So I needed a project that would make best use of real-time data.  
The idea of an online casino seemed to fit the bill as I could have a long running schedule/cron that would make and complete roulette games and update the user.

## Successes

- SSE was successfully implemented via Redis' PubSub system so users avoid polling and creating unnecessary strain on the server.

- Learned moments:
    - Since the roulette game timing is completely predictable it would probably be practically better to just start polling at the end of each minute until we see the latest update. There is a non-zero overhead of keeping these long lived connections open AND since we're using inertia we have to ask the server for the new data anyway.
    - SQLite's inner workings around transactions and its read-guarantees.
    - How to setup and use ESLint.

## Limitations and Frustration

- I'm using Railway as a hosting provider (because it's free). Unfortunately, they have a hard cap of 5 seconds on all non-websocket connections and I only found this out very late into the project.

    - In prod we just fallback to polling.

- Inertiajs completely reworks how api requests are done and needs a change of mental model.

    - It ends up leaking into testing code.
    - It heavily depends on stateful sessions to be able to redirect back to the user's previous url.
        - This caused many hours of frustration when trying to implement SSE as an errorful bet submission would redirect the user to the SSE stream repeatedly instead of the roulett page.

- SQLite doesn't allow for adding most constraints after table creation. LibSQL might be better here.

    - This lead to slightly uglier code as I had to do manual SQL instead of Laravel's typical migration functions.

- PHP's code execution flow is simple but lacks useful things provided by asynchronous programming.

## Tech Stack

- Laravel
- Vuejs
- Inertiajs
- Tailwindcss
- SQLite
- Redis
- Pest (PHPUnit)
