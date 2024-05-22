<template>
  <client-only>
    <div class="relative min-h-screen bg-gray-100 dark:bg-black">
      <main class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
          <!-- Customer account navigation -->
          <KAccountNavigation />

          <!-- Customer account sections -->
          <div class="px-2 sm:px-6 lg:col-span-9 lg:px-0">
            <slot />
          </div>
        </div>
      </main>
    </div>
  </client-only>
</template>
