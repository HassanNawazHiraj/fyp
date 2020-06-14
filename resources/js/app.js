import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";

import Vue from "vue";
import App from "./App.vue";
import router from "./router.js";
import Auth from "./packages/Auth.js";
// import BackendService from "./services/backend-service";

// const backendService = new BackendService();
// Vue.prototype.$backendService = backendService;


Vue.use(Auth);

window.axios = require("axios");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.baseURL = Vue.auth.getBaseUrl();
axios.defaults.headers.common["Authorization"] =
    "Bearer " + Vue.auth.getToken();
window.$ = window.jQuery = require('jquery');

Vue.config.productionTip = false;

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.guest)) {
        if (Vue.auth.isAuthenticated()) {
            next();
        } else next();
    } else if (to.matched.some((record) => record.meta.auth)) {
        if (!Vue.auth.isAuthenticated()) {
            next({
                path: "/",
            });
        } else next();
    } else next();
});

new Vue({
    router,
    render: (h) => h(App),
}).$mount("#app");
