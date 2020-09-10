<template>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    User Roles
                    <a
                        href="#"
                        class="btn btn-primary float-right btn-sm"
                        data-toggle="modal"
                        data-target="#addModal"
                        v-on:click="add()"
                        >Add</a
                    >
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
                                <th>#</th>
                                <th>Name</th>
                                <th>Permission count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{
                                            item.permissions === null
                                                ? "0"
                                                : item.permissions.length
                                        }}
                                    </span>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#add_update_modal"
                                        @click="edit(item)"
                                    >
                                        Edit
                                    </button>
                                    &nbsp;
                                    <button
                                        class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#delete_modal"
                                        @click="remove(item.id)"
                                    >
                                        Delete
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
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize">
                            {{ this.modal_mode }} role
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
                                <span class="input-group-text" id
                                    >Role name</span
                                >
                            </div>
                            <input
                                type="text"
                                class="form-control"
                                v-model="name"
                            />
                        </div>
                        <div class="mt-4">
                            <button
                                class="btn btn-light btn-sm float-right"
                                v-on:click="select_all()"
                            >
                                {{
                                    this.user_permissions.length ==
                                    this.permissions.length
                                        ? "Unselect all"
                                        : "Select all"
                                }}
                            </button>
                            <h4>Permissions</h4>
                        </div>
                        <hr />
                        <div class="row">
                            <div
                                v-for="permission in permissions"
                                :key="permission.name"
                                class="col-4"
                            >
                                <input
                                    type="checkbox"
                                    v-model="user_permissions"
                                    :value="permission.value"
                                />
                                <label>{{ permission.name }}</label>
                            </div>
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
                        Are you sure you want to delete this role?
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
import { permissions as importedPermissions } from "../../permissions.js";
export default {
    data() {
        return {
            items: [],
            name: "",
            user_permissions: [],
            permissions: importedPermissions,
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
        select_all() {
            if (this.user_permissions.length == this.permissions.length) {
                this.user_permissions = [];
            } else {
                this.user_permissions = [];
                this.permissions.forEach(element => {
                    this.user_permissions.push(element.value);
                });
            }
        },
        list() {
            let me = this;
            axios
                .get(me.base_path + "roles")
                .then(response => {
                    me.items = response.data.items;
                    me.items.forEach(item => {
                        item.permissions = JSON.parse(item.permissions);
                    });
                    // console.log(me.items);
                    me.loading = false;
                })
                .catch(function(error) {
                    me.loading = false;
                });
        },
        getUserTypes() {
            let me = this;
            axios
                .get(me.base_path + "user/types")
                .then(response => {
                    me.user_types = response.data.items;
                })
                .catch(error => {
                    for (let key in error.response.data.errors) {
                        if (error.response.data.errors.hasOwnProperty(key)) {
                            me.errors.push(error.response.data.errors[key][0]);
                        }
                    }
                });
        },
        add() {
            this.modal_mode = "add";
            this.name = "";
            this.user_name = "";
            this.user_permissions = [];
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
            formData.set("name", me.name);
            formData.set("permissions", JSON.stringify(me.user_permissions));
            axios
                .post(me.base_path + "roles", formData, {})
                .then(function(response) {
                    if (response.status == 200) {
                        me.closeModal("addModal");
                        me.list();
                        me.toastTitle = "Add";
                        me.toastMessage = "User role added successfully";
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
            this.name = item.name;
            this.user_permissions =
                item.permissions === null ? [] : item.permissions;
            this.id = item.id;
            this.modal_mode = "edit";
            $("#addModal").modal("show");
        },
        update() {
            let me = this;
            me.errors = [];
            let formData = new FormData();
            formData.set("name", me.name);
            formData.set("permissions", JSON.stringify(me.user_permissions));
            formData.set("_method", "PUT");

            axios
                .post(me.base_path + "roles/" + me.id, formData, {})
                .then(function(response) {
                    if (response.status == 200) {
                        me.closeModal("addModal");
                        me.list();
                        me.toastTitle = "Update";
                        me.toastMessage = "Role updated successfully";
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
                .post(me.base_path + "roles/" + me.id, {
                    _method: "DELETE"
                })
                .then(response => {
                    if (response.status == 200) {
                        me.closeModal("deleteModal");
                        me.list();
                        me.toastTitle = "Delete";
                        me.toastMessage = "Role deleted successfully";
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
