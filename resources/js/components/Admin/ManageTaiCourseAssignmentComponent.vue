<template>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Choose a teacher to assign TAI to :
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table table-bordered"
                        id="dataTable"
                        width="100%"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th style="">#</th>
                                <th style="">Course</th>
                                <th style="">Teaching area in-charge</th>
                                <th style="">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td class>
                                    {{ item.title }}
                                </td>
                                <td class="text-capitalize">
                                    <span class="" v-if="item.tai.length > 0">{{item.tai[0].name}}</span>
                                    <span class="badge badge-danger" v-else> Not assigned </span>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#add_update_modal"
                                        data-backdrop="static"
                                        @click="edit(item)"
                                    >
                                        Assign
                                    </button>
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
                            {{ this.modal_mode }} Class courses
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

                        <div class="form-group">
                            <label for="exampleInputEmail1"
                                >Select Course :</label
                            >
                            <v-select
                                multiple
                                v-model="selected"
                                :options="courses"
                                label="name"
                                class="mt-n1 course-select"
                                :closeOnSelect="false"
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
                        <button
                            type="button"
                            class="btn btn-primary"
                            v-on:click="save()"
                        >
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
                        Are you sure you want to delete this Class Course
                        Relation?
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
            style="position: absolute; bottom: 0; right: 0;"
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    components: {
        vSelect
    },
    data() {
        return {
            items: [],
            courses: [],
            selected: [],
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
        list() {
            let me = this;
            axios
                .get(me.base_path + "tai/course")
                .then(response => {
                    me.items = response.data.items;

                })
                .catch(function(error) {
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
                .then(function(response) {
                    if (response.status == 200) {
                        me.closeModal("addModal");
                        me.list();
                        me.toastTitle = "Add";
                        me.toastMessage =
                            "Class Course relation created successfully";
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

        save() {
            if (this.modal_mode == "add") {
                this.create();
            } else {
                this.update();
            }
        },
        edit(item) {
            //console.log(item);
            let me = this;
            this.id = item.id;
            this.selected = [];

            item.class_courses.forEach(element => {
                console.log(element.pivot.course_type);
                if (element.pivot.course_type == "lab") {
                    me.selected.push({
                        id: element.id + "l", //to make id unique. or else you cannot select theory and lab because of same id
                        name:
                            element.course.title +
                            " [" +
                            element.class.batch.season +
                            element.class.batch.year +
                            "-" +
                            element.class.program.short_name +
                            "-" +
                            element.class.section +
                            "] Lab",
                        type: "lab"
                    });
                } else {
                    me.selected.push({
                        id: element.id,
                        name:
                            element.course.title +
                            " [" +
                            element.class.batch.season +
                            element.class.batch.year +
                            "-" +
                            element.class.program.short_name +
                            "-" +
                            element.class.section +
                            "]",
                        type: "theory"
                    });
                }
            });
            this.modal_mode = "edit";
            $("#addModal").modal({ backdrop: "static", keyboard: false });
        },
        update() {
            let me = this;
            me.errors = [];
            let formData = new FormData();
            formData.set("selected_courses", JSON.stringify(me.selected));
            formData.set("_method", "PUT");

            axios
                .post(me.base_path + "teacher_courses/" + me.id, formData, {})
                .then(function(response) {
                    if (response.status == 200) {
                        me.closeModal("addModal");
                        me.list();
                        me.toastTitle = "Update";
                        me.toastMessage = "Course updated successfully";
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
                .post(me.base_path + "class_courses/" + me.id, {
                    _method: "DELETE"
                })
                .then(response => {
                    if (response.status == 200) {
                        me.closeModal("deleteModal");
                        me.list();
                        me.toastTitle = "Delete";
                        me.toastMessage = "Program deleted successfully";
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

<style>
.course-select > #vs1__listbox > .vs__dropdown-option--selected {
    display: none;
}
</style>
