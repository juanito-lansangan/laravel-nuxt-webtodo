import { defineNuxtPlugin } from "#app";
import loader from "vue3-ui-preloader";
import "vue3-ui-preloader/dist/loader.css";
export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.component("VueLoader", loader);
});
