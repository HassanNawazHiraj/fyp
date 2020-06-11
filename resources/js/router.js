import Vue from "vue";
import VueRouter from "vue-router";
import ExampleComponent from "./components/ExampleComponent.vue";
import Test from "./components/Test.vue";
import Login from "./components/Login.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        component: ExampleComponent
    },
    {
        path: "/test",
        component: Test
    },
    {
        path: "/login",
        component: Login
    }
];

const router = new VueRouter({
    routes
});

export default router;
