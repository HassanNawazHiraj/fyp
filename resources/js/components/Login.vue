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
                <div class="alert alert-danger" role="alert" v-if="error.show">{{ error.message }}</div>
                <div class="form-group row">
                  <label
                    for="email_address"
                    class="col-md-4 col-form-label text-md-right"
                  >E-Mail Address</label>
                  <div class="col-md-6">
                    <input
                      type="text"
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
                  <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
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

                <div class="form-group row">
                  <div class="col-md-6 offset-md-4">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="remember" />
                        Remember Me
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 offset-md-8">
                  <button type="submit" class="btn btn-primary" v-on:click="login()">Login</button>
                </div>
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
      if(this.$auth.isAuthenticated()) {
          this.$router.push("/portal");
      }
  },
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
      axios
        .post("/oauth/token", access_data)
        .then(response => {
          console.log(response);
          me.$auth.setToken(
            response.data.access_token,
            Date.now() + response.data.expires_in
          );

          axios.get("/api/user").then(function(response) {
            console.log(response);
            localStorage.setItem("name", response.data.name);
            me.$router.push("/portal");
            //console.log("success");
          });
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
      }
    };
  }
};
</script>

<style scoped>
@import "https://fonts.googleapis.com/css?family=Nunito:400,600|Open+Sans:400,600,700";
@import "https://use.fontawesome.com/releases/v5.4.1/css/all.css";
</style>
