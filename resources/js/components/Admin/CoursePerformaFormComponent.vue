<template>
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
          Course Performa Form Fields
          <a
            href="#"
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
                <th>Field Name</th>
                <th>Field Type</th>
                <th>Actions</th>
              </tr>
            </thead>

            <draggable
              v-model="items"
              tag="tbody"
              v-bind="dragOptions"
              @start="drag = true"
              @end="itemFinishMove"
            >
              <tr v-for="item in items" :key="item.id">
                <td>{{item.id}}</td>
                <td>{{item.name}}</td>
                <td>{{item.field_type}}</td>
                <td>
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
            </draggable>
          </table>
        </div>
      </div>
    </div>

    <!-- Add Form -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add field</h5>
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
                <span class="input-group-text" id>Field Name</span>
              </div>
              <input type="text" class="form-control" v-model="field_name" />
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <label class="input-group-text px-3">Field Type</label>
              </div>
              <select class="custom-select" id="fieldTypeSelect" v-model="field_type">
                <option value="text" selected>Text</option>
                <option value="file">File</option>
              </select>
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
          <div class="modal-body">Are you sure you want to delete this field?</div>
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
    <div style="position: absolute; top: 0; right: 0;" v-bind:class="toastClass">
      <div class="toast m-5" role="alert" data-delay="5000" data-autohide="true">
        <div class="toast-header" v-bind:class="toastClass">
          <strong class="mr-auto">{{toastTitle}}</strong>
          <small class="text-muted ml-5">just now</small>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">{{toastMessage}}</div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
export default {
  computed: {
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    }
  },
  components: {
    draggable
  },
  data() {
    return {
      items: [],
      field_name: "",
      field_type: "text",
      base_path: "/api/",
      errors: [],
      modal_mode: "add",
      id: "",
      toastTitle: "",
      toastMessage: "",
      toastClass: "d-none"
    };
  },
  mounted: function() {
    this.list();
  },
  methods: {
    itemFinishMove() {
      let me = this;
      me.errors = [];
      axios
        .post(me.base_path + "formFields/order", {
          items: this.items
        })
        .then(response => {
          if (response.status == 200) {
          }
          //console.log(response.data);
        })
        .catch(function(error) {
          //console.log(error);
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              me.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    },
    list() {
      let me = this;
      axios
        .get(me.base_path + "formFields")
        .then(response => {
          me.items = response.data.items;
          //console.log(me.items);
          me.loading = false;
        })
        .catch(function(error) {
          me.loading = false;
        });
    },
    add() {
      this.modal_mode = "add";
      this.field_name = "";
      this.field_type = "text";
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
      formData.set("name", me.field_name);
      formData.set("field_type", me.field_type);

      axios
        .post(me.base_path + "formFields", formData, {})
        .then(function(response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list();
            me.toastTitle = "Add";
            me.toastMessage = "Field added successfully";
            me.toastClass = "d-block";
            $(".toast").toast("show");
          }
        })
        .catch(function(error) {
          console.log(error);
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
      this.field_name = item.name;
      this.field_type = item.field_type;
      this.id = item.id;
      this.modal_mode = "edit";
      $("#addModal").modal("show");
    },
    update() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      formData.set("name", me.field_name);
      formData.set("field_type", me.field_type);
      formData.set("_method", "PUT");
      axios
        .post(me.base_path + "formFields/" + me.id, formData, {})
        .then(function(response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list();
            me.toastTitle = "Update";
            me.toastMessage = "Field updated successfully";
            me.toastClass = "d-block";
            $(".toast").toast("show");
          }
        })
        .catch(function(error) {
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
        .post(me.base_path + "formFields/" + me.id, {
          _method: "DELETE"
        })
        .then(response => {
          if (response.status == 200) {
            me.closeModal("deleteModal");
            me.list();
            me.toastTitle = "Delete";
            me.toastMessage = "Field deleted successfully";
            me.toastClass = "d-block";
            $(".toast").toast("show");
          }
        })
        .catch(function(error) {
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              me.errors.push(error.response.data.errors[key][0]);
            }
          }
        });
    }
  }
};
</script>

