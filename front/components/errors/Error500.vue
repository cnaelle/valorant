<template>
  <!-- DEV -->
  <div
    v-if="useRuntimeConfig().public.appEnv !== 'prod' && useRuntimeConfig().public.appEnv !== 'production'"
    class="relative flex flex-col justify-center items-center font-300 cursor-default"
  >
    <span
      class="uppercase text-shadow text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-500 via-cyan-500 to-pink-500"
    >
      <n-gradient-text gradient="linear-gradient(90deg, #79b62a 0%, #30aee4 50%, #de007d 100%)">
        {{ error.statusCode }}
      </n-gradient-text>
    </span>
    <div class="mt-4 text-3xl">
      {{ error.statusMessage || error.message }}
    </div>
    <div
      class="mt-4"
      v-html="error.stack"
    />
  </div>
  <!-- PROD (maintenance) -->
  <div
    v-else
    class="relative flex flex-col justify-center items-center font-300 cursor-default"
  >
    <span
      class="uppercase text-shadow text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-500 via-cyan-500 to-pink-500"
    >
      <n-gradient-text gradient="linear-gradient(90deg, #79b62a 0%, #30aee4 50%, #de007d 100%)">
        Maintenance
      </n-gradient-text>
    </span>
    <div class="mt-4 text-3xl text-center">
      Notre site est en maintenance, nous revenons très vite !
    </div>
  </div>
</template>

<script setup>
defineProps({
  error: Object,
})
</script>

<style lang="scss" scoped>
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.10);
}
</style>
