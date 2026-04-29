import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  ssr: false,
  devtools: { enabled: true },
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