export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxt/ui', '@vueuse/nuxt'],
  css: ['~/assets/css/main.css'],
  icon: {
    clientBundle: {
      icons: [
        'lucide:orbit',
        'lucide:code-xml',
        'lucide:network',
        'lucide:life-buoy',
        'lucide:radio',
        'lucide:send',
        'lucide:check',
        'lucide:rotate-ccw',
        'lucide:triangle-alert',
        'lucide:loader-circle'
      ]
    }
  },
  app: {
    head: {
      htmlAttrs: { lang: 'en' },
      link: [
        { rel: 'preload', href: '/fonts/tektur-latin.woff2', as: 'font', type: 'font/woff2', crossorigin: 'anonymous' },
        { rel: 'preload', href: '/fonts/ibm-plex-sans-latin.woff2', as: 'font', type: 'font/woff2', crossorigin: 'anonymous' }
      ],
      meta: [
        { name: 'theme-color', content: '#070b13' }
      ]
    }
  },
  nitro: {
    devProxy: {
      '/api': {
        target: 'http://127.0.0.1:8081'
      }
    },
    prerender: {
      crawlLinks: true,
      routes: [
        '/',
        '/capabilities',
        '/mission-files',
        '/about',
        '/technology',
        '/contact',
        '/privacy'
      ]
    }
  }
})
