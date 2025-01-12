// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2024-11-01',
    devtools: { enabled: true },
    css: ['~/assets/scss/main.scss'],
    modules: ['@nuxt/icon'],
    runtimeConfig: ({
        API_URL: 'http://localhost:8006',
        public: {
            API_URL: 'http://localhost:8006',
        }
    })
})