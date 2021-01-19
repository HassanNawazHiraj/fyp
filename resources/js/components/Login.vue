<template>
    <div class="form-screen">
        <div class="login-form">
            <div class="cotainer">
                <br />
                <br />
                <br />
                <br />
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">NCEAC Portal Login</div>
                            <div class="card-body">
                                <form @submit="login">
                                    <div
                                        class="alert alert-danger"
                                        role="alert"
                                        v-if="error.show"
                                    >
                                        {{ error.message }}
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="email_address"
                                            class="col-md-4 col-form-label text-md-right"
                                            >E-Mail Address</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="email"
                                                id="email_address"
                                                class="form-control"
                                                name="email-address"
                                                v-model="email"
                                                required
                                                autofocus
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="password"
                                            class="col-md-4 col-form-label text-md-right"
                                            >Password</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="password"
                                                id="password"
                                                class="form-control"
                                                v-model="password"
                                                name="password"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="col-md-6 offset-md-8">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            :disabled="loading"
                                        >
                                            <div
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                v-if="loading"
                                            >
                                                <span class="sr-only"
                                                    >Loading...</span
                                                >
                                            </div>
                                            <span v-if="!loading">Login</span>
                                        </button>
                                    </div>
                                </form>
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
    name: "Login",
    mounted: function() {
        if (this.$auth.isAuthenticated()) {
            this.$router.push("/portal");
        }
    },
    methods: {
        login(e) {
            e.preventDefault();

            this.error.show = false;

            this.loading = true;
            let me = this;
            let formData = new FormData();
            formData.append("email", this.email);
            formData.append("password", this.password);

            axios
                .post("/api/login", formData)
                .then(response => {
                    me.$auth.setToken(response.data.access_token);
                    me.$auth.setUserCookie(true);
                })
                .catch(function(error) {
                    me.loading = false;
                    if (error.response.data.error == "invalid_grant") {
                        me.$auth.destoryToken();
                        me.error.message = "Invalid username or password!";
                        me.error.show = true;
                    }
                });
        }
    },
    data: function() {
        return {
            email: "",
            password: "",
            error: {
                show: false,
                message: "Invalid username or password!"
            },
            loading: false
        };
    }
};
</script>

<style scoped>
@import "https://fonts.googleapis.com/css?family=Nunito:400,600|Open+Sans:400,600,700";
</style>
