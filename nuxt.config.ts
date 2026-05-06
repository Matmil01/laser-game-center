import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  ssr: false,
  devtools: { enabled: true },
  modules: ['@nuxtjs/i18n'],
  i18n: {
    strategy: 'no_prefix', // betyder locale ikke vises i URL, fks /da
    defaultLocale: 'da',
    langDir: 'locales/',
    locales: [
      { code: 'da', language: 'da-DK', name: 'Dansk', file: 'da.json' },
      { code: 'en', language: 'en-GB', name: 'English', file: 'en.json' },
      { code: 'de', language: 'de-DE', name: 'Deutsch', file: 'de.json' },
    ],
    detectBrowserLanguage: false,
  },
  runtimeConfig: {
    public: {
      apiUrl: '', // set in .env
    },
  },
  css: ['~/assets/main.css'],
  vite: {
    plugins: [tailwindcss()],
    server: {
      proxy: {
        '/server-api': {
          target: 'https://laser.matmil.dk',
          changeOrigin: true,
          secure: false,
        }
      }
    }
  },
  app: {
    head: {
      title: 'Laser Game Center',
      meta: [
        { name: 'description', content: 'Velkommen til Laser Game Center!' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: 'icons/favicon.svg' }
      ]
    }
  }
})