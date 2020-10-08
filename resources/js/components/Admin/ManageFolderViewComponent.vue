<template>
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Manage Course Folders</h6>
      </div>
      <div class="card-body">
        <div
          v-if="this.current_position.length > 0"
          class="text-dark mb-1"
        >
          &nbsp;Current Folder :<b>
          {{
            this.current_position.length === 0
              ? ""
              : this.current_position[this.current_position.length - 1]
          }}
          </b>
        </div>
        <div class="table-responsive">
          <table class="table" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td
                  v-if="current_position.length > 0"
                  class="btn-icon btn-icon-primary"
                  @click="move_back()"
                >
                  <i class="fa fa-chevron-left" aria-hidden="true" /> back
                </td>
              </tr>
              <tr v-for="folder in folders" :key="folder">
                <td class="link-folder" @click="open_folder(folder)">
                  <i class="fas fa-folder"></i> &nbsp;{{ folder }}
                </td>
                <td>
                  <span class="badge badge-primary text-center"> Folder </span>
                </td>
                <td>
                  <i
                    class="fa fa-pen fa-lg btn-icon btn-icon-primary"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#add_update_modal"
                    @click="edit(folder, 'folder')"
                  ></i
                  >&nbsp;
                  <i
                    class="fa fa-trash fa-lg btn-icon btn-icon-danger"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#delete_modal"
                    @click="remove(folder, 'folder')"
                  ></i>
                </td>
              </tr>

              <tr v-for="file in files" :key="file">
                <td><i class="fas fa-file"></i> &nbsp;{{ file }}</td>
                <td>
                  <span class="badge badge-primary text-center"> File </span>
                </td>
                <td>
                  <i
                    class="fa fa-download fa-lg btn-icon btn-icon-primary"
                    @click="download(file)"
                    aria-hidden="true"
                  ></i>
                  &nbsp;
                  <i
                    class="fa fa-pen fa-lg btn-icon btn-icon-primary"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#add_update_modal"
                    @click="edit(file, 'file')"
                  ></i
                  >&nbsp;
                  <i
                    class="fa fa-trash fa-lg btn-icon btn-icon-danger"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#delete_modal"
                    @click="remove(file, 'file')"
                  ></i
                  >&nbsp;
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
            <h5 class="modal-title text-capitalize">Rename</h5>
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
                <span class="input-group-text">Name</span>
              </div>
              <input
                type="text"
                class="form-control"
                v-model="current_rename"
              />
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
            Are you sure you want to delete this {{ this.current_delete_type }}?
            <br />
            <span v-if="current_delete_type === 'file'"
              ><i class="fas fa-file"></i> &nbsp;{{ this.current_delete }}</span
            >
            <sapn v-if="current_delete_type === 'folder'"
              ><i class="fas fa-folder"></i> &nbsp;{{
                this.current_delete
              }}</sapn
            >
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
      //this.list(val.id);
    },
  },
  data() {
    return {
      permissions: [],
      items: [],
      files: [],
      folders: [],
      short_name: "",
      full_name: "",
      base_path: "/api/",
      errors: [],
      modal_mode: "add",
      id: "",
      toastTitle: "",
      toastMessage: "",
      toastClass: "d-none",
      main_folder: "",
      current_folder: "",
      current_file: "",
      current_delete: "",
      current_delete_type: "",
      current_type: "",
      //this array stores the current folder level
      current_position: [],
      current_rename: "",
      clicked_file: "",
    };
  },
  mounted: function () {
    this.main_folder = this.$route.params.folder;
    var localPermission = localStorage.getItem("permissions");
    if (localPermission != null) this.permissions = localPermission.split(",");
    this.list();
  },
  methods: {
    list(s = 0) {
      let me = this;

      let path =
        me.base_path +
        "folder/" +
        encodeURI(me.main_folder) +
        "/" +
        encodeURI(JSON.stringify(me.current_position)) +
        "/" +
        encodeURI(me.clicked_file);

      if (s !== 0) {
        path += "?session=" + s;
      }
      axios
        .get(path)
        .then((response) => {
          me.files = response.data.files;
          me.folders = response.data.folders;
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
    save() {
      this.update();
    },
    edit(item, type) {
      this.current_rename = item;
      this.current_type = type;
      if (type == "file") {
        this.current_file = item;
      } else {
        this.current_folder = item;
      }

      $("#addModal").modal("show");
    },
    update() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      console.log(me.current_position);
      formData.set("path", JSON.stringify(me.current_position));

      if (me.current_type == "folder") {
        formData.set("old_name", me.current_folder);
      } else {
        formData.set("old_name", me.current_file);
      }

      formData.set("new_name", me.current_rename);
      formData.set("type", me.current_type);

      axios
        .post(
          me.base_path + "folder/" + me.main_folder + "/rename",
          formData,
          {}
        )
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("addModal");
            me.list();
            me.toastTitle = "Rename";
            me.toastMessage = "Updated successfully";
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
    remove(item, type) {
      this.current_delete = item;
      this.current_delete_type = type;
      $("#deleteModal").modal("show");
    },
    perform_delete() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      formData.set("path", JSON.stringify(me.current_position));

      formData.set("file_name", me.current_delete);

      axios
        .post(
          me.base_path + "folder/" + me.main_folder + "/delete",
          formData,
          {}
        )
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("deleteModal");
            me.list();
            me.toastTitle = "Delete";
            me.toastMessage = "Deleted successfully";
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
    download(current_download) {
      let me = this;

      var path = encodeURI(JSON.stringify(me.current_position));
      var file_name = encodeURI(current_download);

      window.location =
        me.base_path +
        "folder/" +
        me.main_folder +
        "/" +
        path +
        "/" +
        file_name +
        "/download";
    },
    open_folder(folder) {
      this.current_position.push(folder);
      this.list();
    },
    move_back() {
      this.current_position.pop();
      this.list();
    },
  },
};
</script>

<style scoped>
.btn-icon {
  cursor: pointer;
}
.btn-icon-primary:hover {
  color: #007bff;
}
.btn-icon-danger:hover {
  color: #dc3545;
}
.link-folder {
  cursor: pointer;
}
.link-folder:hover {
  color: #007bff;
  text-decoration: underline;
}
</style>
