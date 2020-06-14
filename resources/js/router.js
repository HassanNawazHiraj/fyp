import Vue from "vue";
import VueRouter from "vue-router";
import ExampleComponent from "./components/ExampleComponent.vue";
import Test from "./components/Test.vue";
import Login from "./components/Login.vue";

Vue.use(VueRouter);

const routes = [{
        path: "/",
        component: ExampleComponent,
        meta: {
            guest: true,
        }
    },
    {
        path: "/test",
        component: Test,
        meta: {
            guest: true,
        }
    },
    {
        path: "/login",
        component: Login,
        meta: {
            guest: true,
        }
    }
];

const router = new VueRouter({
    routes,
    mode: 'history'
});

export default router;
