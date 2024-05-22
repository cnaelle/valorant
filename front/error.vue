<template>
  <div
    id="error404"
    class="h-100vh w-full"
  >
    <div class="h-full flex flex-col items-center justify-center">
      <div class="flex items-center justify-center children:mx-auto mt-8">
        <Error404
          v-if="error.statusCode === 404"
          :error="error"
        />
        <Error500
          v-else
          :error="error"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import Error404 from './components/errors/Error404.vue';
import Error500 from './components/errors/Error500.vue';
const props = defineProps({
  error: Object,
})
</script>

<style lang="scss" scoped>
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.10);
}
</style>
