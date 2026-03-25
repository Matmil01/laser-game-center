import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/main.css'],
  vite: {
    plugins: [tailwindcss()]
  },
  app: {
    head: {
      title: 'Laser Game Center',
      meta: [
        { name: 'description', content: 'Velkommen til Laser Game Center!' }
      ],
      // link: [
      //   { rel: 'icon', type: 'image/png', href: '/favicon.png' }
      // ]
    }
  }
})