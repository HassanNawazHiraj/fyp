<template>
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          Class Courses
          <a
            href="#"
            v-if="selectedSession.active"
            class="btn btn-primary float-right btn-sm"
            data-toggle="modal"
            data-target="#addModal"
            v-on:click="add()"
          >Add</a>
        </h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Class</th>
                <th>Course</th>
                <th>Has Lab</th>
                <th v-if="selectedSession.active">Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td>{{ item.id }}</td>
                <td class="text-uppercase">
                  {{
                  (item.class.batch.season.length > 2
                  ? item.class.batch.season.substring(
                  0,
                  2
                  )
                  : item.class.batch.season) +
                  (item.class.batch.year.length > 2
                  ? item.class.batch.year.substring(
                  2,
                  4
                  )
                  : item.class.batch.year) +
                  "-" +
                  item.class.program.short_name +
                  "-" +
                  item.class.section
                  }}
                </td>
                <td class="text-capitalize">{{ item.course.title }}</td>
                <td class="text-center">
                  <span
                    class="badge"
                    v-bind:class="{
                                            'badge-success': item.has_lab,
                                            'badge-danger': !item.has_lab
                                        }"
                  >{{ item.has_lab ? "Yes" : "No" }}</span>
                </td>
                <td v-if="selectedSession.active">
                  <button
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#add_update_modal"
                    @click="edit(item)"
                  >Edit</button>
                  &nbsp;
                  <button
                    class="btn btn-danger btn-sm"
                    data-toggle="modal"
                    data-target="#delete_modal"
                    @click="remove(item.id)"
                  >Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add Form -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-capitalize">{{ this.modal_mode }} Class courses</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div
              v-for="(error, e) in errors"
              :key="e"
              class="alert alert-danger"
              role="alert"
            >{{ error }}</div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Class</span>
              </div>
              <div class="form-control cselect">
                <vSelect
                  label="name"
                  :options="classes"
                  :reduce="c => c.id"
                  v-model="class_id"
                  class="mt-n1"
                ></vSelect>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Course</span>
              </div>
              <div class="form-control cselect">
                <vSelect
                  label="title"
                  :options="courses"
                  :reduce="c => c.id"
                  v-model="course_id"
                  class="mt-n1"
                ></vSelect>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Has Lab</span>
              </div>
              <div class="form-control">
                <input type="checkbox" v-model="has_lab" />
                <label>{{ this.has_lab ? "Yes" : "No" }}</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" v-on:click="save()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this Class Course
            Relation?
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-danger"
              id="deleteModalButton"
              @click="perform_delete()"
            >Delete</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div style="position: absolute; bottom: 0; right: 0;" v-bind:class="toastClass">
      <div class="toast m-5" role="alert" data-delay="5000" data-autohide="true">
        <div class="toast-header" v-bind:class="toastClass">
          <strong class="mr-auto">{{ toastTitle }}</strong>
          <small class="text-muted ml-5">just now</small>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">{{ toastMessage }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  props: ["selectedSession"],
  watch: {
    selectedSession: function (val, oldVal) {
      //console.log("new :" , val.id , " | old : " , oldVal.id);
      this.list(val.id);
    },
  },
  components: {
    vSelect,
  },
  data() {
    return {
      items: [],
      classes: [],
      courses: [],
      class_id: "",
      course_id: "",
      has_lab: false,
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
    this.list(this.selectedSession.id);
  },
  methods: {
    list(s = 0) {
      let path = this.base_path + "class_courses";
      if (s !== 0) {
        path += "?session=" + s;
      }
      let me = this;
      axios
        .get(path)
        .then((response) => {
          me.items = response.data.items;
          me.courses = response.data.courses;
          me.classes = [];
          response.data.classes.forEach((c) => {
            me.classes.push({
              id: c.id,
              name:
                c.batch.season +
                c.batch.year +
                "-" +
                c.program.short_name +
                "-" +
                c.section,
            });
          });

          // console.log(me.items);
          me.loading = false;
        })
        .catch(function (error) {
          me.loading = false;
        });
    },
    add() {
      this.modal_mode = "add";
      this.class_id = "";
      this.course_id = "";
      this.has_lab = false;
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
      formData.set("class_id", me.class_id);
      formData.set("course_id", me.course_id);
      formData.set("has_lab", me.has_lab ? 1 : 0);
      axios
        .post(me.base_path + "class_courses", formData, {})
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list(me.selectedSession.id);
            me.toastTitle = "Add";
            me.toastMessage = "Class Course relation created successfully";
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
      this.class_id = item.class_id;
      this.course_id = item.course_id;
      this.has_lab = item.has_lab;
      this.id = item.id;
      this.modal_mode = "edit";
      $("#addModal").modal("show");
    },
    update() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      formData.set("class_id", me.class_id);
      formData.set("course_id", me.course_id);
      formData.set("has_lab", me.has_lab ? 1 : 0);
      formData.set("_method", "PUT");

      axios
        .post(me.base_path + "class_courses/" + me.id, formData, {})
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list(me.selectedSession.id);
            me.toastTitle = "Update";
            me.toastMessage = "Course updated successfully";
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
        .post(me.base_path + "class_courses/" + me.id, {
          _method: "DELETE",
        })
        .then((response) => {
          if (response.status == 200) {
            me.closeModal("deleteModal");
            me.list(me.selectedSession.id);
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

<style>
.cselect > .v-select .vs__dropdown-toggle {
  border: none;
}
</style>
