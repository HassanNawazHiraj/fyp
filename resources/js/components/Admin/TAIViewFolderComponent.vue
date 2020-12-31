<template>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    View Course Folders
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table"
                        id="dataTable"
                        width="100%"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Owner</th>
                                <th>Checklist</th>
                                <th>Created at</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>
                                    <router-link
                                        :to="
                                            `/portal/view-folder/${
                                                item.class_courses.folder_name
                                            }${
                                                item.course_type == 'lab'
                                                    ? '-l'
                                                    : ''
                                            }`
                                        "
                                    >
                                        <i class="fas fa-folder"></i> &nbsp;{{
                                            item.class_courses.course.title
                                        }}
                                        &nbsp; [{{
                                            item.class_courses.class.batch
                                                .season
                                        }}{{
                                            item.class_courses.class.batch.year
                                        }}-{{
                                            item.class_courses.class.program
                                                .short_name
                                        }}-{{
                                            item.class_courses.class.section
                                        }}]
                                    </router-link>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-primary text-center"
                                    >
                                        {{ item.course_type }}
                                    </span>
                                </td>
                                <td>{{ item.teacher.name }}</td>
                                <td class="text-center">
                                    <i
                                        class="fas fa-th-list btn-icon btn-icon-primary"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                        title="Open Checklist"
                                        @click="
                                            openChecklist(
                                                item.class_courses.folder_name +
                                                    (item.course_type == 'lab'
                                                        ? '-l'
                                                        : '')
                                            )
                                        "
                                        aria-hidden="true"
                                    ></i>
                                </td>
                                <td>
                                    {{
                                        new Date(
                                            item.class_courses.created_at
                                        ).toLocaleString()
                                    }}
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
    </div>
</template>

<script>
export default {
    props: ["selectedSession"],
    watch: {
        selectedSession: function(val, oldVal) {
            this.list(val.id);
        }
    },
    data() {
        return {
            permissions: [],
            items: [],
            base_path: "/api/",
            errors: [],
            checklist: []
        };
    },
    mounted: function() {
        var localPermission = localStorage.getItem("permissions");
        if (localPermission != null)
            this.permissions = localPermission.split(",");
        this.list(this.selectedSession.id);
    },
    methods: {
        onMessageWasSent(message) {
            let me = this;
            me.errors = [];
            let user = JSON.parse(localStorage.getItem("user"));
            let formData = new FormData();
            formData.set("user_id", user.id);
            formData.set("user_type", "teacher");
            formData.set("message", message.data.text);
            axios
                .post(
                    me.base_path + "chat/store/" + this.main_folder,
                    formData,
                    {}
                )
                .then(function(response) {})
                .catch(function(error) {
                    console.log(error);
                });
        },
        loadChat() {
            let me = this;
            me.errors = [];
            let formData = new FormData();

            axios
                .get(
                    me.base_path + "chat/get/" + this.main_folder,
                    formData,
                    {}
                )
                .then(function(response) {
                    if (response.status == 200) {
                        if (response.data.length > 0) {
                            //get both user names
                            var teacher =
                                response.data[0].class_course.teacher_course[0]
                                    .teacher;
                            var tai =
                                response.data[0].class_course.course.tai[0];
                            me.participants.push({
                                id: "teacher",
                                name: teacher.name
                            });
                            me.participants.push({
                                id: "tai",
                                name: tai.name
                            });

                            //get chat data
                            me.messageList = [];
                            response.data.forEach(message => {
                                let user_type =
                                    message.user_type == "teacher"
                                        ? "me"
                                        : "tai";
                                me.messageList.push({
                                    type: "text",
                                    author: user_type,
                                    data: {
                                        text: message.message
                                    }
                                });
                            });
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        list(s = 0) {
            let me = this;
            let path = me.base_path + "tai_course_folders";
            if (s !== 0) {
                path += "?session=" + s;
            }
            axios
                .get(path)
                .then(async response => {
                    const items = response.data.items;
                    console.log("items: ", items);
                    let teacherCourses = [];

                    for await (let item of items) {
                        console.log("item: ", item);
                        for await (let tc of item.teacher_courses) {
                            teacherCourses.push(tc);
                        }
                    }
                    me.items = teacherCourses;
                    me.loading = false;
                })
                .catch(function(error) {
                    me.loading = false;
                });
        },
        closeModal(id) {
            $("#" + id).modal("hide");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        },
        remove(id) {
            this.id = id;
            $("#deleteModal").modal("show");
        },
        getChecklist(folder_name) {
            axios
                .get(this.base_path + "folder/" + folder_name + "/checklist")
                .then(res => {
                    this.checklist = res.data;
                })
                .catch(error => {
                    console.log("error in gettting checklist: ", error);
                });
        },
        openChecklist(folder_name) {
            this.getChecklist(folder_name);
            $("#checklistModal").modal("show");
        }
    }
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
