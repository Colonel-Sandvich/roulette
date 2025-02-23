<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import GitHubLogo from "@/Components/GitHubLogo.vue";
import { Head, Link } from "@inertiajs/vue3";
import { VueComponent as ReadMeComponent } from "../../../README.md";

defineProps<{
  canLogin?: boolean;
  canRegister?: boolean;
  laravelVersion: string;
  phpVersion: string;
}>();
</script>

<template>
  <Head title="Welcome" />
  <div class="flex justify-center">
    <div
      class="flex min-h-screen w-full max-w-2xl flex-col items-center justify-center px-6
        selection:bg-[#FF2D20] selection:text-white lg:max-w-7xl">
      <header class="grid w-full grid-cols-3 items-center py-6">
        <div class="flex">
          <ApplicationLogo class="size-16 fill-current text-gray-500" />
          <a href="https://github.com/Colonel-Sandvich/roulette">
            <GitHubLogo class="size-16 ml-4" />
          </a>
        </div>
        <h1 class="col-start-2 text-center text-xl font-bold">Welcome to Matt's Casino</h1>
        <nav v-if="canLogin" class="flex justify-end gap-4 text-white">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="rounded-md bg-blue-600 px-3 py-2 ring-1 ring-transparent transition
              hover:text-black/50 focus:outline-hidden focus-visible:ring-[#FF2D20]">
            Dashboard
          </Link>

          <template v-else>
            <Link
              :href="route('login')"
              class="rounded-md bg-blue-600 px-3 py-2 ring-1 ring-transparent transition
                hover:text-black/50 focus:outline-hidden focus-visible:ring-[#FF2D20]">
              Log in
            </Link>

            <Link
              v-if="canRegister"
              :href="route('register')"
              class="rounded-md bg-blue-600 px-3 py-2 ring-1 ring-transparent transition
                hover:text-black/50 focus:outline-hidden focus-visible:ring-[#FF2D20]">
              Register
            </Link>
          </template>
        </nav>
      </header>

      <main
        class="grow prose-xl py-10 prose-li:list-disc prose-a:text-blue-600 prose-a:underline
          hover:prose-a:text-blue-500 prose-h2:underline">
        <ReadMeComponent />
      </main>

      <footer class="py-16 text-center text-sm text-black">
        Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
      </footer>
    </div>
  </div>
</template>
