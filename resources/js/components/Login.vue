<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3" />
            <div class="col-md-6">
                <h2 class="h1-responsive font-weight-bold text-center my-4">
                    Log-in
                </h2>

                <form v-on:submit.prevent="login" class="login-form ml-5 mr-5">
                    <div class="form-group">
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            placeholder="Email"
                            v-model="email"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            placeholder="Password"
                            v-model="pass"
                            required
                        />
                    </div>

                    <div class="text-center">
                        <input
                            type="submit"
                            class="btn btn-primary text-light"
                            value="Login"
                        />
                    </div>
                </form>
            </div>
            <div class="col-md-3" />
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",
    methods: {
        login() {
            console.log("login " + this.email + " " + this.pass);
        }
    },
    data: function() {
        return {
            email: "",
            pass: ""
        };
    }
};
</script>
