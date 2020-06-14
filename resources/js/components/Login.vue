<template>
    <div class="form-screen">
        <a class="spur-logo"><i class="fa fa-user-circle"></i> <span>NECEAC Portal Login</span></a>
        <div class="card account-dialog">
            <div class="card-header bg-primary text-white"> Please sign in </div>
            <div class="card-body">
                <div class="alert alert-danger" role="alert" v-if="error.show">{{ error.message }}</div>
                <form v-on:submit.prevent="login">
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Enter email" v-model="email">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" v-model="password">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input">
                            <label class="custom-control-label">Remember me</label>
                        </div>
                    </div>
                    <div class="account-dialog-actions">
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </div>
                </form>
            </div>
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
