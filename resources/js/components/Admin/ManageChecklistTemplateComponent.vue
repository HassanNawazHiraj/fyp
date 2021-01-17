<template>
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          Checklist Template
          <!-- button to update checklist from templates -->
          <button
            class="btn btn-outline-primary float-right btn-sm"
            title="Save Checklist Template"
            @click="updateChecklist"
          >
            Update checklist from template
          </button>
        </h6>
      </div>
      <div class="card-body">
        <div v-for="(task, index) in checklist" :key="index" class="row mb-1">
          <div class="input-group mb-1 col-sm-12 col-lg-6">
            <div class="input-group-prepend">
              <span class="input-group-text">Label</span>
            </div>
            <input type="text" class="form-control" v-model="task.label" />
          </div>
          <div class="input-group mb-1 col-sm-10 col-lg-5">
            <div class="input-group-prepend">
              <span class="input-group-text">Default Value</span>
            </div>
            <div class="form-control">
              <div
                class="custom-control custom-switch"
                @click="
                  () => {
                    task.value = !task.value;
                  }
                "
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  v-model="task.value"
                />
                <label class="custom-control-label">{{ task.value }}</label>
              </div>
              <!-- <input type="checkbox" v-model="task.value" />
                            <label>{{task.value}}</label> -->
            </div>
          </div>
          <div class="col-sm-2 col-lg-1">
            <button
              class="btn btn-outline-danger btn-sm"
              title="Remove Task"
              @click="removeTask(index)"
            >
              <i class="far fa-trash-alt"></i>
            </button>
          </div>
        </div>
        <div class="d-flex flex-row-reverse mt-2 pt-2 border-top">
          <button
            class="btn btn-outline-primary btn-sm"
            title="Save Checklist Template"
            @click="saveTemplate"
          >
            <i class="far fa-save"></i> &nbsp; Save
          </button>
          <button
            class="btn btn-outline-success btn-sm mr-2"
            title="Add new label"
            @click="addTask"
          >
            <i class="fas fa-plus"></i> &nbsp; Add Label
          </button>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div style="position: absolute; top: 0; right: 0" v-bind:class="toastClass">
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
  data() {
    return {
      base_path: "/api/",
      errors: [],
      permissions: [],
      checklist: [],
      toastClass: "d-none",
      toastTitle: "",
      toastMessage: "",
    };
  },
  mounted() {
    var localPermission = localStorage.getItem("permissions");
    if (localPermission != null) this.permissions = localPermission.split(",");
    this.getTemplateChecklist();
  },
  methods: {
    getTemplateChecklist() {
      axios
        .get(this.base_path + "folder/checklist_template")
        .then((res) => {
          this.checklist = res.data;
        })
        .catch((error) => {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              this.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },
    addTask() {
      this.checklist.push({ label: "", value: false });
    },
    removeTask(index) {
      if (index > -1) this.checklist.splice(index, 1);
    },
    saveTemplate() {
      axios
        .post(this.base_path + "folder/checklist_template", {
          checklist: this.checklist,
        })
        .then((res) => {
          this.toastTitle = "Update";
          this.toastMessage = "Template updated successfully";
          this.toastClass = "d-block";
          $(".toast").toast("show");
        })
        .catch((error) => {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              this.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },

    updateChecklist() {
        //update checklist from template
        axios
        .get(this.base_path + "folder/update_checklist_from_template")
        .then((res) => {
          this.toastTitle = "Update";
          this.toastMessage = "Template updated successfully";
          this.toastClass = "d-block";
          $(".toast").toast("show");
          this.getTemplateChecklist();
        })
        .catch((error) => {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              this.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },
  },
};
</script>

<style></style>
