<template>
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Course Folders</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Owner</th>
                <th>Created at</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td>
                  <router-link :to="`/portal/view/${item.class_courses.folder_name}`">
                    <i class="fas fa-folder"></i> &nbsp;{{
                      item.class_courses.course.title
                    }}
                    &nbsp; [{{ item.class_courses.class.batch.season
                    }}{{ item.class_courses.class.batch.year }}-{{
                      item.class_courses.class.program.short_name
                    }}-{{ item.class_courses.class.section }}]
                  </router-link>
                </td>
                <td>
                  <span class="badge badge-primary text-center">
                    {{ item.course_type }}
                  </span>
                </td>
                <td>{{ item.teacher.name }}</td>
                <td>
                  {{
                    item.class_courses.folder
                      ? new Date(
                          item.class_courses.folder.created_at
                        ).toLocaleString()
                      : ""
                  }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add Form -->
    <div
      class="modal fade"
      id="addModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-capitalize">
              {{ this.modal_mode }} program
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div
              v-for="(error, e) in errors"
              :key="e"
              class="alert alert-danger"
              role="alert"
            >
              {{ error }}
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Short Name</span>
              </div>
              <input type="text" class="form-control" v-model="short_name" />
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Full Name</span>
              </div>
              <input type="text" class="form-control" v-model="full_name" />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button type="button" class="btn btn-primary" v-on:click="save()">
              Save
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete modal -->
    <div
      class="modal fade"
      id="deleteModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this program?
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-danger"
              id="deleteModalButton"
              @click="perform_delete()"
            >
              Delete
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div
      style="position: absolute; bottom: 0; right: 0"
      v-bind:class="toastClass"
    >
      <div
        class="toast m-5"
        role="alert"
        data-delay="5000"
        data-autohide="true"
      >
        <div class="toast-header" v-bind:class="toastClass">
          <strong class="mr-auto">{{ toastTitle }}</strong>
          <small class="text-muted ml-5">just now</small>
          <button
            type="button"
            class="ml-2 mb-1 close"
            data-dismiss="toast"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">{{ toastMessage }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["selectedSession"],
  watch: {
    selectedSession: function (val, oldVal) {
      //console.log("new :" , val.id , " | old : " , oldVal.id);
      this.list(val.id);
    },
  },
  data() {
    return {
      permissions: [],
      items: [],
      short_name: "",
      full_name: "",
      base_path: "/api/",
      errors: [],
      modal_mode: "add",
      id: "",
      toastTitle: "",
      toastMessage: "",
      toastClass: "d-none",
    };
  },
  mounted: function () {
    var localPermission = localStorage.getItem("permissions");
    if (localPermission != null) this.permissions = localPermission.split(",");
    this.list(this.selectedSession.id);
  },
  methods: {
    list(s = 0) {
      let me = this;
      let path = me.base_path + "teacher/courses";
      if (s !== 0) {
        path += "?session=" + s;
      }
      axios
        .get(path)
        .then((response) => {
          me.items = response.data.items;
          // console.log(me.items);
          me.loading = false;
        })
        .catch(function (error) {
          me.loading = false;
        });
    },
    add() {
      this.modal_mode = "add";
      this.short_name = "";
      this.full_name = "";
    },
    closeModal(id) {
      $("#" + id).modal("hide");
      $("body").removeClass("modal-open");
      $(".modal-backdrop").remove();
    },
    create() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      formData.set("short_name", me.short_name);
      formData.set("full_name", me.full_name);
      axios
        .post(me.base_path + "program", formData, {})
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list();
            me.toastTitle = "Add";
            me.toastMessage = "Program added successfully";
            me.toastClass = "d-block";
            $(".toast").toast("show");
          }
        })
        .catch(function (error) {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              me.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },

    save() {
      if (this.modal_mode == "add") {
        this.create();
      } else {
        this.update();
      }
    },
    edit(item) {
      this.short_name = item.short_name;
      this.full_name = item.full_name;
      this.id = item.id;
      this.modal_mode = "edit";
      $("#addModal").modal("show");
    },
    update() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      formData.set("short_name", me.short_name);
      formData.set("full_name", me.full_name);
      formData.set("_method", "PUT");

      axios
        .post(me.base_path + "program/" + me.id, formData, {})
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list();
            me.toastTitle = "Update";
            me.toastMessage = "Program updated successfully";
            me.toastClass = "d-block";
            $(".toast").toast("show");
          }
        })
        .catch(function (error) {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              me.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },
    remove(id) {
      this.id = id;
      $("#deleteModal").modal("show");
    },
    perform_delete() {
      let me = this;
      me.errors = [];
      axios
        .post(me.base_path + "program/" + me.id, {
          _method: "DELETE",
        })
        .then((response) => {
          if (response.status == 200) {
            me.closeModal("deleteModal");
            me.list();
            me.toastTitle = "Delete";
            me.toastMessage = "Program deleted successfully";
            me.toastClass = "d-block";
            $(".toast").toast("show");
          }
        })
        .catch(function (error) {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              me.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },
  },
};
</script>
