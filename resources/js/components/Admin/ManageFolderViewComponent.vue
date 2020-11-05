<template>
  <div class="container-fluid">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <div class="row">
          <div class="col">
            <h6 class="m-0 font-weight-bold text-primary">
              Manage Course Folders
            </h6>
          </div>
          <div class="col">
            <button
              class="btn btn-outline-primary btn-sm float-right"
              @click="upload()"
              data-toggle="tooltip"
              data-placement="bottom"
              title="Upload new file"
            >
              <i class="fa fa-upload" aria-hidden="true"></i> &nbsp; Upload
            </button>

            <button
              class="btn btn-outline-primary btn-sm float-right mr-2"
              @click="show_folder_modal()"
              data-toggle="tooltip"
              data-placement="bottom"
              title="Create new folder"
            >
              <i class="fa fa-folder" aria-hidden="true"></i> &nbsp; New folder
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div v-if="this.current_position.length > 0" class="text-dark mb-1">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
              <li
                class="breadcrumb-item"
                v-for="p in current_position"
                :key="p"
                :class="{ active: isActive }"
              >
                {{ p }}
              </li>
            </ol>
          </nav>
        </div>
        <div v-if="to_move_file != ''" style="position: relative">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
              <li>
                File selected for move : <i class="far fa-file-alt mr-1"></i
                ><b>{{ to_move_file }}</b>
              </li>
              <li></li>
              <button
                class="btn btn-outline-primary btn-sm"
                style="position: absolute; right: 1rem; top: 0.5rem"
                @click="move_file_process()"
              >
                Move here
              </button>
            </ol>
          </nav>
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
                    class="fa fa-download fa-lg btn-icon btn-icon-primary mr-1"
                    @click="zip(folder)"
                    aria-hidden="true"
                    title="Zip folder"
                  ></i>
                  <i
                    class="fa fa-pen fa-lg btn-icon btn-icon-primary"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#add_update_modal"
                    @click="edit(folder, 'folder')"
                    title="Rename"
                  ></i
                  >&nbsp;
                  <i
                    class="fa fa-trash fa-lg btn-icon btn-icon-danger"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#delete_modal"
                    @click="remove(folder, 'folder')"
                    title="Delete"
                  />
                </td>
              </tr>

              <tr v-for="file in files" :key="file">
                <td class="link-folder" @click="viewFile(file)">
                  <i class="fas fa-file"></i> &nbsp;{{ file }}
                </td>
                <td>
                  <span class="badge badge-primary text-center"> File </span>
                </td>
                <td>
                  <i
                    class="fas fa-arrows-alt fa-lg btn-icon btn-icon-primary mr-1"
                    @click="move_file(file)"
                    aria-hidden="true"
                    title="Move"
                  ></i>
                  <i
                    class="fa fa-download fa-lg btn-icon btn-icon-primary"
                    @click="download(file)"
                    aria-hidden="true"
                    title="Download"
                  ></i>
                  &nbsp;
                  <i
                    class="fa fa-pen fa-lg btn-icon btn-icon-primary"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#add_update_modal"
                    @click="edit(file, 'file')"
                    title="Rename"
                  ></i
                  >&nbsp;
                  <i
                    class="fa fa-trash fa-lg btn-icon btn-icon-danger"
                    aria-hidden="true"
                    data-toggle="modal"
                    data-target="#delete_modal"
                    @click="remove(file, 'file')"
                    title="Delete"
                  ></i
                  >&nbsp;
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- create folder -->
    <div
      class="modal fade"
      id="folderModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-capitalize">New folder</h5>
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
              <input type="text" class="form-control" v-model="new_folder" />
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
            <button
              type="button"
              class="btn btn-primary"
              v-on:click="create_folder()"
            >
              Create
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- rename Form -->
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

    <!-- upload modal -->
    <div
      class="modal fade"
      id="uploadModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Upload</h5>
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
            <uploader
              ref="uploader"
              :options="{
                target: '/api/folder/upload',
                testChunks: false,
                headers: this.headers,
                processParams: this.processParams,
              }"
              v-on:complete="this.complete"
              :autoStart="true"
              class="uploader-example"
            >
              <uploader-unsupport></uploader-unsupport>
              <uploader-drop>
                <p>Drop files here to upload or</p>
                <uploader-btn>select files</uploader-btn>
                <!-- <uploader-btn :directory="true">select folder</uploader-btn> -->
              </uploader-drop>
              <uploader-list></uploader-list>
            </uploader>
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
    let me = this;
    return {
      permissions: [],
      new_folder: "",
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
      to_move_file: "",
      to_move_path: [],
      current_type: "",
      //this array stores the current folder level
      current_position: [],
      current_rename: "",
      clicked_file: "",
      attrs: {
        accept: "image/*",
      },
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      processParams: (params, file, chunk, isTest) => {
        params.path = JSON.stringify(this.current_position);
        params.folder = this.main_folder;
        return params;
      },
      complete: () => {
        this.list();
      },
      isActive: (p) => {
        return this.current_position[current_position.length - 1] === p;
      },
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
    create_folder() {
      let me = this;
      me.errors = [];
      let formData = new FormData();
      //console.log(me.current_position);
      formData.set("path", JSON.stringify(me.current_position));
      formData.set("name", me.new_folder);

      axios
        .post(me.base_path + "folder/" + me.main_folder + "/new", formData, {})
        .then(function (response) {
          if (response.status == 200) {
            me.closeModal("folderModal");
            me.list();
            me.toastTitle = "Rename";
            me.toastMessage = "Folder created successfully";
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
    upload() {
      const uploaderInstance = this.$refs.uploader.uploader;
      uploaderInstance.cancel();
      $("#uploadModal").modal("show");
    },

    show_folder_modal() {
      this.new_folder = "";
      $("#folderModal").modal("show");
    },

    viewFile(current_file) {
      let me = this;

      var path = encodeURI(JSON.stringify(me.current_position));
      var file_name = encodeURI(current_file);
      let url =
        me.base_path +
        "folder/" +
        me.main_folder +
        "/" +
        path +
        "/" +
        file_name +
        "/download";

      window.open(
        `https://docs.google.com/gview?url=${window.location.origin}${url}&embedded=true`
      );
    },
    move_file(current_file) {
      this.to_move_file = current_file;
      this.to_move_path = Object.assign([], this.current_position);
    },
    move_file_process() {
      //process moving of file
      let formData = new FormData();
      formData.set("main_folder", this.main_folder);
      formData.set("to_path", JSON.stringify(this.current_position));
      formData.set("from_path", JSON.stringify(this.to_move_path));
      formData.set("file", this.to_move_file);
      let me = this;
      axios
        .post(this.base_path + "folder/file/move", formData)
        .then((response) => {
          me.list();
          me.to_move_file = "";
        })
        .catch(function (error) {});
    },
    zip(folder) {
      let me = this;

      var path = encodeURI(JSON.stringify(me.current_position));
      var folder = encodeURI(folder);

      window.location =
        me.base_path +
        "folder/" +
        me.main_folder +
        "/" +
        path +
        "/" +
        folder +
        "/zip";
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
