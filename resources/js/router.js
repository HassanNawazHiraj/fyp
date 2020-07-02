import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./components/Home.vue";
import Login from "./components/Login.vue";
import Layout from "./components/Layout.vue";
import AdminLayout from "./components/Admin/AdminLayout.vue";
import DashboardComponent from "./components/Admin/DashboardComponent.vue";
import CoursePerformaFormComponent from "./components/Admin/CoursePerformaFormComponent.vue";
import ManageUsersComponent from "./components/Admin/ManageUsersComponent.vue";
Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        component: Layout,
        meta: {
            guest: true
        },
        children: [
            {
                path: "",
                component: Login
            }
        ]
    },
    {
        path: "/portal",
        component: AdminLayout,
        meta: {
            auth: true
        },
        children: [
            {
                path: "",
                component: DashboardComponent
            },
            {
                path: "course-performa-form",
                component: CoursePerformaFormComponent
            },
            {
                path: "manage-users",
                component: ManageUsersComponent
            }
        ]
    }
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
});

export default router;
