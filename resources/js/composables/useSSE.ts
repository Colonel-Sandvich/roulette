import { onMounted, onUnmounted, ref } from "vue";

type Callback = () => void;

export function useSSE(url: RequestInfo | URL) {
  const eventListeners = ref<Record<string, Callback>>({});
  let abortController: AbortController | null = null;
  // If the server closes unexpectedly `reader.read()` will hang indefinitely
  // We should add a heartbeat timeout that will attempt to reconnect
  let heartbeatTimeout: number | undefined = undefined;

  onMounted(startSSE);
  onUnmounted(stopSSE);

  async function startSSE() {
    abortController = new AbortController();
    const signal = abortController.signal;
    resetHearbeat();

    try {
      const response = await fetch(url, {
        headers: { accept: "text/event-stream" },
        signal,
      });

      if (!response.body) throw new Error("No response body");

      const textStream = response.body.pipeThrough(new TextDecoderStream());
      const reader = textStream.getReader();

      while (true) {
        const { value, done } = await reader.read();
        if (done) {
          break;
        }

        resetHearbeat();

        const lines = value.split("\n");

        for (const line of lines) {
          if (line.startsWith("event: ")) {
            const event = line.replace("event: ", "").trim();
            eventListeners.value[event]?.();
          }
        }
      }
    } catch (error) {
      if (!signal.aborted) {
        console.error("SSE stream error:", error);
        setTimeout(startSSE, 3000);
      }
    } finally {
      // If we got here then the SSE is done or we're reconnecting already
      // We don't want the heartbeat also trying to reconnect
      clearTimeout(heartbeatTimeout);
    }
  }

  function stopSSE() {
    if (abortController) {
      abortController.abort();
    }
  }

  function resetHearbeat() {
    clearTimeout(heartbeatTimeout);
    heartbeatTimeout = setTimeout(
      () => {
        stopSSE();
        void startSSE();
      },
      (+import.meta.env.ROULETTE_GAME_LENGTH_IN_SECONDS + 5) * 1000,
    );
  }

  function addEventListener(eventType: string, callback: () => void) {
    eventListeners.value[eventType] = callback;
  }

  function removeEventListener(eventType: string) {
    delete eventListeners.value[eventType];
  }

  return { startSSE, stopSSE, addEventListener, removeEventListener };
}
