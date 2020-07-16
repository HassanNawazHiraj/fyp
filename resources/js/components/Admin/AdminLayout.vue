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
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
          <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div class="sidebar-brand-text mx-2">NCEAC Portal</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0" />

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <router-link class="nav-link" to="/portal">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </router-link>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Interface</div>

        <!-- Nav Item - Pages Collapse Menu -->

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/course-performa-form"
            v-if="permissions.includes('course_performa_form_view')"
          >
            <i class="fas fa-fw fa-table"></i>
            <span>Course Performa Form</span>
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-users"
            v-if="permissions.includes('user_view')"
          >
            <i class="fa fa-user-edit"></i>
            <span>Manage Users</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-roles"
            v-if="permissions.includes('user_role_view')"
          >
            <i class="fa fa-user-tag"></i>
            <span>Manage User Roles</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-batch"
            v-if="permissions.includes('batch_view')"
          >
            <i class="fa fa-users"></i>
            <span>Manage Batch</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-program"
            v-if="permissions.includes('program_view')"
          >
            <i class="fa fa-graduation-cap"></i>
            <span>Manage Program</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-course"
            v-if="permissions.includes('course_view')"
          >
            <i class="fa fa-book"></i>
            <span>Manage Course</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-class"
            v-if="permissions.includes('class_view')"
          >
            <i class="fa fa-users"></i>
            <span>Manage Class</span>
          </router-link>
        </li>
        <div class="sidebar-heading">Assignments</div>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-course-assignment"
            v-if="permissions.includes('class_courses_view')"
          >
            <i class="fa fa-users"></i>
            <span>Assign course to class</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-teacher-assignment"
            v-if="permissions.includes('class_courses_view')"
          >
            <i class="fa fa-users"></i>
            <span>Assign course to teacher</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/portal/manage-course-assignment"
            v-if="permissions.includes('class_courses_view')"
          >
            <i class="fa fa-users"></i>
            <span>Assign TAI to teacher</span>
          </router-link>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block" />

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
      </ul>
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <form
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
            </form>

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
                  <form class="form-inline mr-auto w-100 navbar-search">
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
                  >{{ user_login_name }}</span>
                </a>
                <!-- Dropdown - User Information -->
                <div
                  class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                  aria-labelledby="userDropdown"
                >
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                  </a>
                </div>
              </li>
            </ul>
          </nav>
          <!-- End of Topbar -->

          <!-- Begin Page Content -->
          <router-view></router-view>
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
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            Select "Logout" below if you are ready to end your
            current session.
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" v-on:click="logout()">Logout</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      permissions: [],
      user_login_name: ""
    };
  },
  methods: {
    logout() {
      this.$auth.destoryToken();
      window.location = "/";
      //this.$router.push("/");
    }
  },
  mounted: function() {
    this.user_login_name = localStorage.getItem("name");
    this.$auth.setUserCookie();
    var localPermission = localStorage.getItem("permissions");
    if (localPermission != null) this.permissions = localPermission.split(",");
  }
};
</script>

<style>
@import url("/css/sb-admin-2.min.css");
</style>
