import { onMounted, onUnmounted, ref } from "vue";

type Callback = () => void;

export function useSSE(url: RequestInfo | URL) {
  const isStreaming = ref(false);
  const eventListeners = ref<Record<string, Callback>>({});
  let abortController: AbortController | null = null;

  onMounted(startSSE);
  onUnmounted(stopSSE);

  async function startSSE() {
    if (isStreaming.value) {
      return;
    }
    isStreaming.value = true;

    abortController = new AbortController();
    const signal = abortController.signal;

    try {
      const response = await fetch(url, {
        headers: { accept: "text/event-stream" },
        signal,
      });

      if (!response.body) throw new Error("No response body");

      const textStream = response.body.pipeThrough(new TextDecoderStream());
      const reader = textStream.getReader();

      let buffer = "";

      while (isStreaming.value) {
        const { value, done } = await reader.read();
        if (done) {
          break;
        }

        buffer += value;

        console.log(buffer);

        const lines = buffer.split("\n");
        buffer = lines.pop() || ""; // Keep unfinished line

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
    }
  }

  function stopSSE() {
    isStreaming.value = false;
    if (abortController) {
      abortController.abort();
    }
  }

  function addEventListener(eventType: string, callback: () => void) {
    eventListeners.value[eventType] = callback;
  }

  function removeEventListener(eventType: string) {
    delete eventListeners.value[eventType];
  }

  return { isStreaming, startSSE, stopSSE, addEventListener, removeEventListener };
}
