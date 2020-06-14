<template>
  <div class="container">
    <div class="row">
      <div class="col-md-3" />
      <div class="col-md-6">
        <h2 class="h1-responsive font-weight-bold text-center my-4">Log-in</h2>
        <div class="alert alert-danger" role="alert" v-if="error.show">{{ error.message }}</div>
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
              v-model="password"
              required
            />
          </div>

          <div class="text-center">
            <input type="submit" class="btn btn-primary text-light" value="Login" />
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
      this.loading = true;
      let me = this;
      let access_data = {
        client_id: 2,
        client_secret: "FpLFIh7RRQeeJpmHj6tChq7yg85KNb8Chr1zpAVu",
        grant_type: "password",
        username: this.email,
        password: this.password
      };
      axios.post("/oauth/token", access_data).then(response => {
        console.log(response);
        me.$auth.setToken(
          response.data.access_token,
          Date.now() + response.data.expires_in
        );

        axios
          .get("/api/user")
          .then(function(response) {
            console.log(response);
            localStorage.setItem("name", response.data.name);
            me.$router.push('/portal')
            //console.log("success");
          })

      }).catch(function(error) {
        me.loading = false;
        if (error.response.data.error == "invalid_grant") {
          me.$auth.destoryToken();
          me.error.message = "Invalid username or password!";
          me.error.show = true;
        }
      })
    }
  },
  data: function() {
    return {
      email: "",
      password: "",
      error: {
        show: false,
        message: "Invalid username or password!"
      }
    };
  }
};
</script>
