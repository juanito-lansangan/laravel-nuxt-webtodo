// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    ssr: false,
    compatibilityDate: '2024-11-01',
    // devtools: { enabled: true },
    // devServer: {
    //     host: "myapp.test"
    // },
    css: [
        '~/assets/scss/main.scss',
    ],
    modules: [
        '@nuxt/icon',
        'nuxt3-notifications',
        '@qirolab/nuxt-sanctum-authentication'
    ],
    laravelSanctum: {
        // apiUrl: 'http://localhost:8006', // Laravel API
        apiUrl: 'https://api-todo.stjudeappraisal.io', // Laravel API
        // authMode: "cookie",
        authMode: "token",
        userResponseWrapperKey: "data",

        token: {
            storageKey: "AUTH_TOKEN",
            provider: "localStorage",
            responseKey: "token",
        },
        sanctumEndpoints: {
            // Endpoint to request a new CSRF token from the server
            csrf: "/sanctum/csrf-cookie",

            // Endpoint used for user authentication
            login: "/api/login",

            // Endpoint used to log out users
            logout: "/api/logout",

            // Endpoint to retrieve the currently authenticated user's data
            user: "/api/user",
        },

        redirect: {
            // Preserve the originally requested route, redirecting users there after login
            enableIntendedRedirect: false,

            // Path to redirect users when a page requires authentication
            loginPath: "/login",

            // URL to redirect users to when guest-only access is required
            // guestOnlyRedirect: "/login",

            // URL to redirect to after a successful login
            redirectToAfterLogin: "/",

            // URL to redirect to after logging out
            redirectToAfterLogout: "/",
        },
    },
    runtimeConfig: {
        public: {
            apiUrl: 'http://localhost:8006', // Expose to client
        },
    },
})