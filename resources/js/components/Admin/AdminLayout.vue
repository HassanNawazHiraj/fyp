<template>
    <div id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            <ul
                class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
                id="accordionSidebar"
            >
                <!-- Sidebar - Brand -->
                <router-link
                    class="sidebar-brand d-flex align-items-center justify-content-center"
                    to="/portal"
                >
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="sidebar-brand-text mx-2">NCEAC Portal</div>
                </router-link>

                <!-- Divider -->
                <hr
                    class="sidebar-divider my-0"
                    v-if="permissions.includes('view_dashboard')"
                />

                <!-- Nav Item - Dashboard -->
                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal' }
                    ]"
                    v-if="permissions.includes('view_dashboard')"
                >
                    <router-link class="nav-link" to="/portal">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </router-link>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider" />

                <!-- Heading -->
                <div
                    v-if="
                        permissions.includes('course_performa_form_view') ||
                            permissions.includes('user_view') ||
                            permissions.includes('user_role_view') ||
                            permissions.includes('batch_view') ||
                            permissions.includes('program_view') ||
                            permissions.includes('course_view') ||
                            permissions.includes('class_view') ||
                            permissions.includes('session_view') ||
                            permissions.includes('checklist_template')
                    "
                    class="sidebar-heading"
                >
                    Actions
                </div>

                <!-- Nav Item - Pages Collapse Menu -->

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path ===
                                '/portal/course-performa-form'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/course-performa-form"
                        v-if="permissions.includes('course_performa_form_view')"
                    >
                        <i class="fas fa-fw fa-table"></i>
                        <span>Course Performa Form</span>
                    </router-link>
                </li>
                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/manage-users' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-users"
                        v-if="permissions.includes('user_view')"
                    >
                        <i class="fa fa-user-edit"></i>
                        <span>Manage Users</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/manage-roles' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-roles"
                        v-if="permissions.includes('user_role_view')"
                    >
                        <i class="fa fa-user-tag"></i>
                        <span>Manage User Roles</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/manage-batch' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-batch"
                        v-if="permissions.includes('batch_view')"
                    >
                        <i class="fa fa-users"></i>
                        <span>Manage Batch</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path === '/portal/manage-program'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-program"
                        v-if="permissions.includes('program_view')"
                    >
                        <i class="fa fa-graduation-cap"></i>
                        <span>Manage Program</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/manage-course' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-course"
                        v-if="permissions.includes('course_view')"
                    >
                        <i class="fa fa-book"></i>
                        <span>Manage Course</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/manage-class' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-class"
                        v-if="permissions.includes('class_view')"
                    >
                        <i class="fa fa-chalkboard-teacher"></i>
                        <span>Manage Class</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path === '/portal/manage-session'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-session"
                        v-if="permissions.includes('session_view')"
                    >
                        <i class="fa fa-stopwatch"></i>
                        <span>Manage Session</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path ===
                                '/portal/manage-checklist-template'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-checklist-template"
                        v-if="permissions.includes('checklist_template')"
                    >
                        <i class="fas fa-th-list"></i>
                        <span>Manage Checklist Template</span>
                    </router-link>
                </li>

                <div
                    class="sidebar-heading"
                    v-if="permissions.includes('assigned_teachers_view')"
                >
                    Information
                </div>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path ===
                                '/portal/view-assigned-teachers'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/view-assigned-teachers"
                        v-if="permissions.includes('assigned_teachers_view')"
                    >
                        <i class="fa fa-id-card-alt"></i>
                        <span>View Assigned Teachers</span>
                    </router-link>
                </li>

                <div
                    v-if="
                        permissions.includes('class_courses_view') ||
                            permissions.includes('course_teacher_view') ||
                            permissions.includes('tai_course_view')
                    "
                    class="sidebar-heading"
                >
                    Assignments
                </div>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path ===
                                '/portal/manage-course-assignment'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-course-assignment"
                        v-if="permissions.includes('class_courses_view')"
                    >
                        <i class="fa fa-object-group"></i>
                        <span>Assign course to class</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path ===
                                '/portal/manage-teacher-assignment'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-teacher-assignment"
                        v-if="permissions.includes('course_teacher_view')"
                    >
                        <i class="fa fa-user-shield"></i>
                        <span>Assign course to teacher</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        {
                            active:
                                this.$route.path ===
                                '/portal/manage-tai-assignment'
                        }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-tai-assignment"
                        v-if="permissions.includes('tai_course_view')"
                    >
                        <i class="fa fa-book-reader"></i>
                        <span>Assign TAI to Course</span>
                    </router-link>
                </li>

                <div
                    v-if="
                        permissions.includes('folder_view') ||
                            permissions.includes('tai_folder_view')
                    "
                    class="sidebar-heading"
                >
                    Files & Folders
                </div>

                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/manage-folder' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/manage-folder"
                        v-if="permissions.includes('folder_view')"
                    >
                        <i class="fa fa-folder-plus"></i>
                        <span>Manage your Course Folders</span>
                    </router-link>
                </li>

                <li
                    :class="[
                        'nav-item',
                        { active: this.$route.path === '/portal/view-folder' }
                    ]"
                >
                    <router-link
                        class="nav-link"
                        to="/portal/view-folder"
                        v-if="permissions.includes('tai_folder_view')"
                    >
                        <i class="fa fa-folder-open"></i>
                        <span>View assigned Course Folders</span>
                    </router-link>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block" />

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button
                        class="rounded-circle border-0"
                        id="sidebarToggle"
                    ></button>
                </div>
            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <nav
                        class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
                    >
                        <!-- Sidebar Toggle (Topbar) -->
                        <button
                            id="sidebarToggleTop"
                            class="btn btn-link d-md-none rounded-circle mr-3"
                        >
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Search -->
                        <!-- <form
              class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
            >
              <div class="input-group">
                <input
                  type="text"
                  class="form-control bg-light border-0 small"
                  placeholder="Search for..."
                  aria-label="Search"
                  aria-describedby="basic-addon2"
                />
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                  </button>
                </div>
              </div>
            </form>-->

                        <!-- Session selection dropdown -->
                        <div class="col-md-6">
                            <v-select
                                v-model="selectedSession"
                                :options="sessions"
                                label="name"
                                class="session-select w-100"
                                :clearable="false"
                                placeholder="Select a session"
                            />
                        </div>

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="searchDropdown"
                                    role="button"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div
                                    class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                    aria-labelledby="searchDropdown"
                                >
                                    <form
                                        class="form-inline mr-auto w-100 navbar-search"
                                    >
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control bg-light border-0 small"
                                                placeholder="Search for..."
                                                aria-label="Search"
                                                aria-describedby="basic-addon2"
                                            />
                                            <div class="input-group-append">
                                                <button
                                                    class="btn btn-primary"
                                                    type="button"
                                                >
                                                    <i
                                                        class="fas fa-search fa-sm"
                                                    ></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - Alerts -->

                            <!-- Nav Item - Messages -->

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="userDropdown"
                                    role="button"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <span
                                        class="mr-2 d-none d-lg-inline text-gray-600 small"
                                        id="user_name"
                                        >{{ user_login_name }}</span
                                    >
                                </a>
                                <!-- Dropdown - User Information -->
                                <div
                                    class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown"
                                >
                                    <router-link
                                        class="dropdown-item"
                                        to="/portal/settings"
                                    >
                                        <i
                                            class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"
                                        />
                                        Settings
                                    </router-link>
                                    <div class="dropdown-divider"></div>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                        data-toggle="modal"
                                        data-target="#logoutModal"
                                    >
                                        <i
                                            class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                                        ></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <router-view
                        v-bind:selectedSession="selectedSession"
                    ></router-view>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2019</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div
            class="modal fade"
            id="logoutModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Ready to Leave?
                        </h5>
                        <button
                            class="close"
                            type="button"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Select "Logout" below if you are ready to end your
                        current session.
                    </div>
                    <div class="modal-footer">
                        <button
                            class="btn btn-secondary"
                            type="button"
                            data-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button class="btn btn-primary" v-on:click="logout()">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    components: {
        vSelect
    },
    data() {
        return {
            base_path: "/api/",
            permissions: [],
            types: [],
            user_login_name: "",
            selectedSession: {},
            sessions: []
        };
    },
    methods: {
        logout() {
            this.$auth.destoryToken();
            window.location = "/";
            //this.$router.push("/");
        },
        isTai() {
            this.types.forEach(user => {
                if (user.name == "Teaching area in-charge") {
                    return true;
                }
            });
            return false;
        },
        getSessions() {
            axios.get(this.base_path + "session").then(res => {
                // console.log("data: ", res.data.items);
                let sessions = [];
                res.data.items.forEach(s => {
                    s.name = s.season + " " + s.year;
                    if (s.active) {
                        s.name += " (active)";
                        this.selectedSession = s;
                    }
                    sessions.push(s);
                });
                this.sessions = sessions;
                // console.log("sessions: ", sessions);
            });
        }
    },
    mounted: function() {
        this.user_login_name = localStorage.getItem("name");
        this.$auth.setUserCookie();
        var localPermission = localStorage.getItem("permissions");
        if (localPermission != null)
            this.permissions = localPermission.split(",");
        this.types = JSON.parse(localStorage.getItem("types"));

        this.getSessions();

        this.$root.$on("refreshSession", () => {
            this.getSessions();
        });

        //console.log(this.types);
    }
};
</script>

<style>
@import url("/css/sb-admin-2.min.css");
.session-select .vs__dropdown-option--selected {
    display: none;
}
</style>
