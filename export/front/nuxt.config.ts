import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import Icons from 'unplugin-icons/vite'
import { NaiveUiResolver } from 'unplugin-vue-components/resolvers'
import { pwa } from './config/pwa'
import { getBrandColors } from '@kronos/core/utils/branding'

const colors = getBrandColors()

export default defineNuxtConfig({
  modules: [
    '@kronos/core',
    '@nuxt/image-edge',
    '@vueuse/nuxt',
    '@unocss/nuxt',
    '@pinia-plugin-persistedstate/nuxt',
    '@pinia/nuxt',
    '@nuxtjs/color-mode',
    '@nuxtjs/device',
    '@vite-pwa/nuxt',
    '@nuxt/devtools',
    'unplugin-icons/nuxt'
  ],
  experimental: {
    // when using generate, payload js assets included in sw precache manifest
    // but missing on offline, disabling extraction it until fixed
    payloadExtraction: false,
    inlineSSRStyles: false,
  },
  css: [
    '@/assets/css/app.scss',
    '@/assets/css/reset/tailwind.css',
  ],
  colorMode: {
    classSuffix: '',
  },
  nitro: {
    esbuild: {
      options: {
        target: 'esnext',
      },
    },
    prerender: {
      crawlLinks: false,
      // routes: ['/'],
      // ignore: ['/hi'],
    },
  },
  app: {
    head: {
      viewport: 'width=device-width,initial-scale=1',
      link: [
        { rel: 'icon', href: '/favicon.png', sizes: 'any' },
        // { rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg' },
        { rel: 'apple-touch-icon', href: '/apple-touch-icon.png' },
      ],
      meta: [
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'apple-mobile-web-app-status-bar-style', content: 'black-translucent' },
        { name: 'theme-color', content: colors.primary['500'] },
        { name: 'msapplication-navbutton-color', content: colors.primary['500'] },
        { name: 'apple-mobile-web-app-status-bar-style', content: colors.primary['500'] },
      ],
    },
  },
  pwa,
  runtimeConfig: {
    public: {
      theme: {
        common: {
          primaryColor: colors.primary['500'],
          primaryColorHover: colors.primary['700'],
          primaryColorPressed: colors.primary['700'],
          primaryColorSuppl: colors.primary['500'],
          secondaryColor: colors.secondary['500'],
          secondaryColorHover: colors.secondary['700'],
          secondaryColorPressed: colors.secondary['700'],
          secondaryColorSuppl: colors.secondary['500'],
          borderRadius: '8px',
        },
        Button: {
          paddingMedium: '1.3rem 15px',
          paddingLarge: '1.5rem 18px',
          fontSizeLarge: '16px',
        },
        Form: {
          feedbackPadding: '2px 0 0 2px',
          feedbackHeightSmall: '18px',
          feedbackHeightMedium: '18px',
          feedbackHeightLarge: '18px',
          feedbackFontSizeSmall: '12px',
          feedbackFontSizeMedium: '12px',
          feedbackFontSizeLarge: '12px',
        },
      },
      recaptcha: {
        v2SiteKey: process.env.RECAPTCHA_V2_SITEKEY,
        v3SiteKey: process.env.RECAPTCHA_V3_SITEKEY,
      },
      apiBaseUrl: process.env.API_BASE_URL,
      appName: process.env.APP_NAME,
      appEnv: process.env.APP_ENV,
      formidableFormsApiKey: process.env.PLUGIN_FORMIDABLE_FORMS
    },
  },
  plugins: [
    { src: '@kronos/core/plugins/css-vars', mode: 'client' },
    '@kronos/core/plugins/i18n',
    '@kronos/core/plugins/middlewares',
    '@kronos/core/plugins/mitt',
    '@kronos/core/plugins/naive',
  ],
  build: {
    transpile:
      process.env.NODE_ENV === 'production'
        ? [
          'naive-ui',
          'vueuc',
          '@css-render/vue3-ssr',
          'juggle/resize-observer',
          'date-fns'
        ]
        : ['@juggle/resize-observer', '@kronos/core'],
  },
  vite: {
    optimizeDeps: {
      include: process.env.NODE_ENV === 'development' ? ['naive-ui', 'vueuc', 'date-fns-tz/esm/formatInTimeZone', 'fast-deep-equal'] : [],
    },
    plugins: [
      Icons({
        autoInstall: true,
      }),
      AutoImport({
        imports: [
          {
            'naive-ui': ['useDialog', 'useMessage', 'useNotification', 'useLoadingBar'],
          },
        ],
      }),
      Components({
        resolvers: [NaiveUiResolver()],
      }),
    ],
  },
})
