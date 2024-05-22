<template>
  <div class="relative flex flex-col justify-center items-center font-300 cursor-default">
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
    <NuxtLink
      to="/"
      class="mt-12 flex items-center justify-center text-gray-4"
    >
      <div class="flex items-center mt--3 text-xl">
        <div class="w-8 i-carbon-arrow-left mr-2" />
        Retour à l'accueil
      </div>
    </NuxtLink>
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
