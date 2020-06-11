import Vue from "vue";
import router from "./router";

import App from "./components/App.vue";
import ExampleComponent from "./components/ExampleComponent";
import Test from "./components/Test.vue";

require("./bootstrap");

const app = new Vue({
    el: "#app",
    components: {
        App,
        ExampleComponent,
        Test
    },
    router
});
