import axios from 'axios';

export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'linov-comunity',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1.0' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
      { name: 'google-site-verification', content: '_IhHtm8akWy1q8JYtAn5qbWMcT-HeHzzBe5oYurJ5xo' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: "stylesheet",
        href: "https://fonts.googleapis.com/css2?family=Inter:wght@300&display=swap",
      },
      {
        rel: "stylesheet",
        href: "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css",
      },
      {
        rel: "stylesheet",
        href: "https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css",
      },
    ],
    script: [
      { src: 'https://code.jquery.com/jquery-2.2.4.min.js', type: 'text/javascript', body: true, defer: true },
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js', type: 'text/javascript', body: true, defer: true },
      { src: 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', type: 'text/javascript', body: true, defer: true },
    ],
  },

  ssr: true,
  
  publicRuntimeConfig: {
    baseURL: process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV
  },
  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/css/bootstrap.min.css',
    '~/assets/css/lightbox.css',
    '~/assets/css/main.css',
    '~/assets/css/style.css',
  ],
  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '~/plugins/axios',
    '~/plugins/services',
    '~/plugins/utils',
    '~/plugins/tooltip.js',
    '~/plugins/gtag.js',
    '~/plugins/tel-input.client.js',
    '~/plugins/multiselect.client.js',
    '~/plugins/toast.client.js',
    '~/plugins/avatar.client.js',
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,
  loading: '~/components/loading-dialog.vue',

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  build: {
    postcss: null,
    filenames: {
      app: ({ isDev }) => isDev ? '[name].js' : '[chunkhash].js',
      chunk: ({ isDev }) => isDev ? '[name].js' : '[chunkhash].js',
    },
    loaders: {
      vue: {
        compiler: require('vue-template-babel-compiler')
      }
    },
  },
    

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/dayjs',
    '@nuxtjs/sitemap'
  ],

  sitemap: {
    gzip: true,
    defaults: {
      changefreq: 'daily',
      priority: 1,
      lastmod: new Date(),
    },
    routes: async () => {
      const baseUrl =  process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV
      let sitemaps = []
      await axios.get(baseUrl+'/api/sitemap/article').then(response=>{
        sitemaps = response.data.map((slug) => `/insight/${slug}`)
      })
      await axios.get(baseUrl+'/api/sitemap/event').then(response=>{
        sitemaps = [...sitemaps,...response.data.map((slug) => `/training/event/${slug}`)]
      })
      return sitemaps
    },
  },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    baseURL: process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV,
    credentials: true
  },

  dayjs: {
    locales: ['en', 'id'],
    defaultLocale: 'id',
    plugins: ['duration','calendar', 'weekday', 'weekOfYear']
  },
  // router: {
  //   base:process.env.NODE_ENV === "production" ? '/comunity/' : "/"
  // },
  auth: {
    strategies: {
      laravelSanctum: {
        provider: 'laravel/sanctum',
        url: process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV
      },
    }
  },
}
