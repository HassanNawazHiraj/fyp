<template>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Choose a course to assign Teaching area in-charge
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
                                    <span class="" v-if="item.tai.length > 0">{{
                                        item.tai[0].name
                                    }}</span>
                                    <span class="badge badge-danger" v-else>
                                        Not assigned
                                    </span>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#add_update_modal"
                                        data-backdrop="static"
                                        @click="assign(item)"
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

        <!-- Assignment Form -->
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
                            Assign Course Teaching area in-charge
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
                            <label>Select Teaching area in-charge</label>
                            <v-select
                                :options="tais"
                                :reduce="t => t.id"
                                v-model="tai_id"
                                label="name"
                                class="mt-n1 tai-select"
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
            tais: [],
            tai_id: "",
            course_id: "",
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
                .get(me.base_path + "tai_courses")
                .then(response => {
                    me.items = response.data.items;
                    axios
                        .get(me.base_path + "user/tais")
                        .then(response => {
                            me.tais = response.data.items;
                        })
                        .catch(error => {
                            console.log(error);
                        });
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
        assign(item) {
            this.course_id = item.id;
            if (item.tai.length > 0) {
                this.tai_id = item.tai[0].id;
                this.modal_mode = "edit";
            } else {
                this.modal_mode = "add";
            }
            $("#addModal").modal({ backdrop: "static", keyboard: false });
        },
        save() {
            this.errors = [];
            if (this.modal_mode === "add") {
                this.create();
            } else {
                if (this.tai_id !== null) {
                    this.update();
                } else {
                    this.errors.push("Please select a value");
                }
            }
        },
        create() {
            let formData = new FormData();
            formData.set("course_id", this.course_id);
            formData.set("tai_id", this.tai_id);

            axios
                .post(this.base_path + "tai_courses", formData, {})
                .then(response => {
                    if (response.status === 200) {
                        this.closeModal("addModal");
                        this.list();
                        this.toastTitle = "Add";
                        this.toastMessage =
                            "Teaching area in-charge assigned successfully";
                        this.toastClass = "d-block";
                        $(".toast").toast("show");
                    }
                })
                .catch(error => {
                    for (let key in error.response.data.errors) {
                        if (error.response.data.errors.hasOwnProperty(key)) {
                            me.errors.push(error.response.data.errors[key][0]);
                        }
                    }
                });
        },
        update() {
            let formData = new FormData();
            formData.set("course_id", this.course_id);
            formData.set("tai_id", this.tai_id);
            formData.set("_method", "PUT");

            axios
                .post(
                    this.base_path + "tai_courses/" + this.course_id,
                    formData,
                    {}
                )
                .then(response => {
                    if (response.status === 200) {
                        this.closeModal("addModal");
                        this.list();
                        this.toastTitle = "Update";
                        this.toastMessage =
                            "Teaching area in-charge assigned successfully";
                        this.toastClass = "d-block";
                        $(".toast").toast("show");
                    }
                })
                .catch(error => {
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
