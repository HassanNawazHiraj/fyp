import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./components/Home.vue";
import Login from "./components/Login.vue";
import Layout from "./components/Layout.vue";
import AdminLayout from "./components/Admin/AdminLayout.vue";
import DashboardComponent from "./components/Admin/DashboardComponent.vue";
import CoursePerformaFormComponent from "./components/Admin/CoursePerformaFormComponent.vue";
import ManageUsersComponent from "./components/Admin/ManageUsersComponent.vue";
import ManageUserRolesComponent from "./components/Admin/ManageUserRolesComponent.vue";
import ManageBatchComponent from "./components/Admin/ManageBatchComponent.vue";
import ManageProgramComponent from "./components/Admin/ManageProgramComponent.vue";
import ManageCourseComponent from "./components/Admin/ManageCourseComponent.vue";
import ManageClassComponent from "./components/Admin/ManageClassComponent.vue";
import ManageCourseAssignmentComponent from "./components/Admin/ManageCourseAssignmentComponent.vue";
import ManageCourseTeacherAssignmentComponent from "./components/Admin/ManageCourseTeacherAssignmentComponent.vue";
import ManageTaiCourseAssignmentComponent from "./components/Admin/ManageTaiCourseAssignmentComponent.vue";
import ViewAssignedTeachersComponent from "./components/Admin/ViewAssignedTeachersComponent.vue";
import ManageSessionComponent from "./components/Admin/ManageSessionComponent.vue";
import ManageFolderComponent from "./components/Admin/ManageFolderComponent.vue";
import ManageFolderViewComponent from "./components/Admin/ManageFolderViewComponent.vue";
import ManageChecklistTemplateComponent from "./components/Admin/ManageChecklistTemplateComponent.vue";
import TAIViewFolderComponent from "./components/Admin/TAIViewFolderComponent.vue";
import TAIFolderViewComponent from "./components/Admin/TAIFolderViewComponent.vue";

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
                permission: {
                    name: "course_performa_form_view"
                },
                component: CoursePerformaFormComponent
            },
            {
                path: "manage-users",
                component: ManageUsersComponent
            },
            {
                path: "manage-roles",
                component: ManageUserRolesComponent
            },
            {
                path: "manage-batch",
                component: ManageBatchComponent
            },
            {
                path: "manage-program",
                component: ManageProgramComponent
            },
            {
                path: "manage-course",
                component: ManageCourseComponent
            },
            {
                path: "manage-class",
                component: ManageClassComponent
            },
            {
                path: "manage-course-assignment",
                component: ManageCourseAssignmentComponent
            },
            {
                path: "manage-teacher-assignment",
                component: ManageCourseTeacherAssignmentComponent
            },
            {
                path: "manage-tai-assignment",
                component: ManageTaiCourseAssignmentComponent
            },
            {
                path: "view-assigned-teachers",
                component: ViewAssignedTeachersComponent
            },
            {
                path: "manage-session",
                component: ManageSessionComponent
            },
            {
                path: "manage-folder",
                component: ManageFolderComponent
            },
            {
                path: "view/:folder",
                component: ManageFolderViewComponent
            },
            {
                path: "manage-checklist-template",
                component: ManageChecklistTemplateComponent
            },
            {
                path: "view-folder",
                component: TAIViewFolderComponent
            },
            {
                path: "view-folder/:folder",
                component: TAIFolderViewComponent
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
