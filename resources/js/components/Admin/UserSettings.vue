<template>
    <div class="container">
        <div class="text-primary h1">Settings</div>
        <form @submit="updateName" class="card">
            <div class="card-header">
                <div class="card-title text-primary">Update Name</div>
            </div>
            <div class="card-body">
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                    v-if="change_name_success"
                >
                    {{ change_name_success }}
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                    v-for="(error, index) in name_errors"
                    :key="index"
                >
                    {{ error }}
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" v-model="name" />
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-outline-primary">
                    Update
                </button>
            </div>
        </form>
        <hr />
        <form @submit="changePassword" class="card mb-5">
            <div class="card-header">
                <div class="card-title text-primary">Change Password</div>
            </div>
            <div class="card-body">
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                    v-if="change_password_success"
                >
                    {{ change_password_success }}
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                    v-for="(error, index) in password_errors"
                    :key="index"
                >
                    {{ error }}
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="form-group">
                    <label>Old Password</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="old_password"
                    />
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="new_password"
                    />
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="password_confirmation"
                    />
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-outline-primary">
                    Change
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: localStorage.getItem("name"),
            old_password: "",
            new_password: "",
            password_confirmation: "",
            name_errors: [],
            password_errors: [],
            change_name_success: "",
            change_password_success: ""
        };
    },
    methods: {
        updateName(e) {
            e.preventDefault();
            let me = this;

            me.name_errors = [];
            me.change_name_success = "";

            let formData = new FormData();
            formData.append("name", me.name);

            axios
                .post("/api/user/change-name", formData)
                .then(res => {
                    me.change_name_success = "Name Updated Successfully.";
                    localStorage.setItem("name", me.name);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                })
                .catch(error => {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.errors
                    ) {
                        for (let key in error.response.data.errors) {
                            if (
                                error.response.data.errors.hasOwnProperty(key)
                            ) {
                                me.name_errors.push(
                                    error.response.data.errors[key][0]
                                );
                            }
                        }
                    } else if (error.response.data.message) {
                        me.name_errors.push(error.response.data.message);
                    } else {
                        me.name_errors.push(
                            "An error occured while updating name."
                        );
                    }
                });
        },
        changePassword(e) {
            e.preventDefault();
            let me = this;

            me.password_errors = [];
            me.change_password_success = "";

            let formData = new FormData();
            formData.append("old_password", me.old_password);
            formData.append("new_password", me.new_password);
            formData.append(
                "new_password_confirmation",
                me.password_confirmation
            );

            axios
                .post("/api/user/change-password", formData)
                .then(res => {
                    me.change_password_success =
                        "Password Changed Successfully.";
                    me.old_password = "";
                    me.new_password = "";
                    me.password_confirmation = "";
                })
                .catch(error => {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.errors
                    ) {
                        for (let key in error.response.data.errors) {
                            if (
                                error.response.data.errors.hasOwnProperty(key)
                            ) {
                                me.password_errors.push(
                                    error.response.data.errors[key][0]
                                );
                            }
                        }
                    } else {
                        me.password_errors.push(
                            "An error occured while changing password."
                        );
                    }
                });
        }
    }
};
</script>
