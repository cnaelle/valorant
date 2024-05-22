<template>
  <VitePwaManifest />
  <NConfigProvider
    :locale="frFR"
    :date-locale="dateFrFR"
    :theme-overrides="runtimeConfig.public.theme"
  >
    <NDialogProvider>
      <NMessageProvider :max="3">
        <NNotificationProvider :max="3">
          <NuxtLayout>
            <NuxtLoadingIndicator :height="4" />
            <NuxtPage />
          </NuxtLayout>
        </NNotificationProvider>
      </NMessageProvider>
    </NDialogProvider>
  </NConfigProvider>
</template>

<script setup>
import { useAuthStore } from '@kronos/core/stores/auth'
import { frFR, dateFrFR } from 'naive-ui'
import { setDefaultOptions } from 'date-fns'
import { fr } from 'date-fns/locale'

const route = useRoute()
const auth = useAuthStore()
const runtimeConfig = useRuntimeConfig()

setDefaultOptions({ locale: fr })

// Verify email if an activation key is provided
if (route.query.activation_key) {
  auth.verifyUserEmail(route.query.activation_key)
}
</script>
