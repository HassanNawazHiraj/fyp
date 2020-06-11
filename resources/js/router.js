import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

export default VueRouter({
    routes: [{
        path: '/',
        component: () =>
            import ("./components/ExampleComponent.vue"),
    }],
    mode: 'history'
});