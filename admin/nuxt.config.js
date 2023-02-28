export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'linov-community-admin',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: "stylesheet",
        href: "https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap",
      },
    ],
    script: [
      // { src: 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js', type: 'text/javascript', body: true, defer: true },
      // { src: 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js', type: 'text/javascript', body: true, defer: true },
      { src: '/vendor/js/helpers.js', type: 'text/javascript', body: true, defer: false },
      { src: '/js/config.js', type: 'text/javascript', body: true, defer: false },
      { src: '/vendor/js/bootstrap.js', type: 'text/javascript', body: true, defer: false },
      { src: '/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', type: 'text/javascript', body: true, defer: false },
      { src: '/vendor/js/menu.js', type: 'text/javascript', body: true, defer: false },
      // { src: '/js/main.js', type: 'text/javascript', body: true, defer: false },
    ]
  },

  ssr: false,
  
  publicRuntimeConfig: {
    baseURL: process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/vendor/fonts/boxicons.css',
    '~/assets/vendor/css/core.css',
    '~/assets/vendor/css/theme-default.css',
    '~/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css',
    '~/assets/vendor/css/pages/page-auth.css',
    '~/assets/css/main.css',
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '~/plugins/utils.js',
    '~/plugins/services',
    '~/plugins/toast.js',
    '~/plugins/select.js',
    '~/plugins/editor.js',
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
   
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/dayjs',
  ],

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    baseURL: process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV,
    credentials: true
  },
  //   router: {
  //   base:process.env.NODE_ENV === "production" ? '/comunity-admin/' : "/"
  // },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    postcss: null,
    filenames: {
      chunk: ({ isDev }) => (isDev ? '[name].js' : '[id].[contenthash].js')
    },
    loaders: {
      loader: 'vue-style-loader!css-loader',
      vue: {
        compiler: process.env.NODE_ENV !== 'production' ? require('vue-template-babel-compiler') : null
      }
    },
  },

  auth: {
    redirect: {
      login: '/login',
      logout: '/logout',
      home: false
    },
    strategies: {
      laravelSanctum: {
        provider: 'laravel/sanctum',
        url: process.env.NODE_ENV === 'production' ? process.env.BACKEND_URL : process.env.BACKEND_URL_DEV
      },
    }
  }
}
