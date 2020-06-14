import Vue from "vue";
import VueRouter from "vue-router";
import Home from './components/Home.vue';
import Login from './components/Login.vue';
import Layout from './components/Layout.vue';
import AdminLayout from './components/Admin/AdminLayout.vue';
import DashboardComponent from './components/Admin/Dashboardcomponent.vue';
Vue.use(VueRouter);

const routes = [{
        path: "/",
        component: Layout,
        children: [{
            path: "",
            component: Login,
            meta: {
                guest: true,
            }
        }, ],
    },
    {
        path: "/portal",
        component: AdminLayout,
        meta: {
            auth: true,
        },
        childern: [{
            path: "",
            component: DashboardComponent,
        }]
    }

];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,
});

export default router;
