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
              @click="openChecklist()"
              data-toggle="tooltip"
              data-placement="bottom"
              title="Open Checklist"
            >
              <i class="fas fa-th-list" aria-hidden="true"></i>
              &nbsp; Checklist
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
                File selected for move :
                <i class="far fa-file-alt mr-1"></i><b>{{ to_move_file }}</b>
              </li>
              <li></li>
              <button
                class="btn btn-outline-primary btn-sm"
                style="position: absolute; right: 5rem; top: 0.5rem"
                @click="move_file_process()"
              >
                Move here
              </button>
              <button
                class="btn btn-outline-danger btn-sm"
                style="position: absolute; right: 1rem; top: 0.5rem"
                @click="
                  () => {
                    to_move_file = '';
                  }
                "
              >
                Cancel
              </button>
            </ol>
          </nav>
        </div>
        <div v-if="to_move_folder != ''" style="position: relative">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
              <li>
                Folder selected for move :
                <i class="far fa-file-alt mr-1"></i><b>{{ to_move_folder }}</b>
              </li>
              <li></li>
              <button
                class="btn btn-outline-primary btn-sm"
                style="position: absolute; right: 5rem; top: 0.5rem"
                @click="move_folder_process()"
              >
                Move here
              </button>
              <button
                class="btn btn-outline-danger btn-sm"
                style="position: absolute; right: 1rem; top: 0.5rem"
                @click="
                  () => {
                    to_move_folder = '';
                  }
                "
              >
                Cancel
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
                  <i class="fa fa-chevron-left" aria-hidden="true" />
                  back
                </td>
              </tr>
              <tr v-for="folder in folders" :key="folder">
                <td class="link-folder" @click="open_folder(folder)">
                  <i class="fas fa-folder"></i> &nbsp;{{ folder }}
                </td>
                <td>
                  <span class="badge badge-primary text-center"> Folder </span>
                </td>
                <td class="text-center">
                  <i
                    class="fa fa-download fa-lg btn-icon btn-icon-primary mr-1"
                    @click="zip(folder)"
                    aria-hidden="true"
                    title="Zip folder"
                  ></i>
                </td>
              </tr>

              <tr v-for="file in files" :key="file">
                <td class="link-folder" @click="viewFile(file)">
                  <i class="fas fa-file"></i> &nbsp;{{ file }}
                </td>
                <td>
                  <span class="badge badge-primary text-center"> File </span>
                </td>
                <td class="text-center">
                  <i
                    class="fa fa-download fa-lg btn-icon btn-icon-primary"
                    @click="download(file)"
                    aria-hidden="true"
                    title="Download"
                  ></i>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- checklist -->
    <div
      class="modal fade"
      id="checklistModal"
      tabindex="-1"
      role="dialog"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-capitalize">Check List</h5>
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
            <div class="pl-4">
              <div
                class="checklist form-group"
                v-for="(item, index) in checklist"
                :key="index"
              >
                <input
                  type="checkbox"
                  class="form-check-input"
                  v-model="item.value"
                  onclick="return false;"
                />
                <label>{{ item.label }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- chat -->
    <beautiful-chat
      :participants="participants"
      :onMessageWasSent="onMessageWasSent"
      :messageList="messageList"
      :newMessagesCount="0"
      :isOpen="chat_status"
      :close="
        () => {
          chat_status = false;
        }
      "
      :open="
        () => {
          chat_status = true;
        }
      "
      :colors="colors"
      :showEmoji="false"
      :showFile="false"
      :showEdition="false"
      :showDeletion="false"
      showTypingIndicator=""
      :showLauncher="true"
      :showCloseButton="true"
      :alwaysScrollToBottom="true"
    />
  </div>
</template>

<script>
export default {
  props: ["selectedSession"],
  watch: {
    selectedSession: function (val, oldVal) {},
  },
  data() {
    let me = this;
    return {
      chat_status: false,
      messageList: [],
      participants: [],
      colors: {
        header: {
          bg: "#4e8cff",
          text: "#ffffff",
        },
        launcher: {
          bg: "#4e8cff",
        },
        messageList: {
          bg: "#ffffff",
        },
        sentMessage: {
          bg: "#4e8cff",
          text: "#ffffff",
        },
        receivedMessage: {
          bg: "#eaeaea",
          text: "#222222",
        },
        userInput: {
          bg: "#f4f7f9",
          text: "#565867",
        },
      },
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
      to_move_folder: "",
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
      checklist: [],
      interval: "",
    };
  },

  mounted: function () {
    this.main_folder = this.$route.params.folder;
    var localPermission = localStorage.getItem("permissions");
    if (localPermission != null) this.permissions = localPermission.split(",");
    this.list();

    this.loadChat();

    this.interval = setInterval(
      function () {
        this.loadChat();
      }.bind(this),
      3000
    );
  },
  destroyed: function () {
    clearInterval(this.interval);
  },
  methods: {
    onMessageWasSent(message) {
      let me = this;
      me.errors = [];
      let user = JSON.parse(localStorage.getItem("user"));
      let formData = new FormData();
      formData.set("user_id", user.id);
      formData.set("user_type", "tai");
      formData.set("message", message.data.text);
      axios
        .post(me.base_path + "chat/store/" + this.main_folder, formData, {})
        .then(function (response) {})
        .catch(function (error) {
          console.log(error);
        });
    },
    loadChat() {
      let me = this;
      me.errors = [];
      let formData = new FormData();

      axios
        .get(me.base_path + "chat/get/" + this.main_folder, formData, {})
        .then(function (response) {
          if (response.status == 200) {
            if (response.data.length > 0) {
              //get both user names
              var teacher =
                response.data[0].class_course.teacher_course[0].teacher;
              var tai = response.data[0].class_course.course.tai[0];
              me.participants.push({
                id: "teacher",
                name: teacher.name,
              });
              me.participants.push({
                id: "tai",
                name: tai.name,
              });

              //get chat data
              me.messageList = [];
              response.data.forEach((message) => {
                let user_type = message.user_type == "tai" ? "me" : "teacher";
                me.messageList.push({
                  type: "text",
                  author: user_type,
                  data: {
                    text: message.message,
                  },
                });
              });
            }
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
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
    closeModal(id) {
      $("#" + id).modal("hide");
      $("body").removeClass("modal-open");
      $(".modal-backdrop").remove();
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
    getChecklist() {
      axios
        .get(this.base_path + "folder/" + this.main_folder + "/checklist")
        .then((res) => {
          this.checklist = res.data;
        })
        .catch((error) => {
          console.log("error in gettting checklist: ", error);
        });
    },
    openChecklist() {
      this.getChecklist();
      $("#checklistModal").modal("show");
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
