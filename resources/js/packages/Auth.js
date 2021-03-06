export default function(Vue) {
    Vue.auth = {
        getBaseUrl() {
            return "http://fyp.test/";
        },
        setToken(token, expiration) {
            try {
                localStorage.setItem("token", token);
                localStorage.setItem("expiration", expiration);
            } catch (e) {
                return false;
            }
            axios.defaults.headers.common["Authorization"] = "Bearer " + token;
        },
        checkPermission(permission) {},
        setUserCookie(redirect = false) {
            axios.get("/api/user").then(function(response) {
                localStorage.setItem("name", response.data.user.name);
                localStorage.setItem("user", JSON.stringify(response.data.user));
                localStorage.setItem("types", response.data.types);
                localStorage.setItem("permissions", response.data.permissions);
                // ret = "asd";
                //console.log("success");
                if (redirect) {
                    window.location = "/portal";
                }
            });
        },
        getToken() {
            let token;
            let expiration;
            try {
                token = localStorage.getItem("token");
                expiration = localStorage.getItem("expiration");
            } catch (e) {
                return false;
            }

            if (!token) return null;

            // if(Date.now() > parseInt(expiration)){
            // this.destoryToken()
            // return null
            // }

            return token;
        },

        destoryToken() {
            try {
                localStorage.removeItem("token");
                localStorage.removeItem("expiration");
                localStorage.removeItem("permissions");
                localStorage.removeItem("type");
            } catch (e) {
                return false;
            }
        },

        isAuthenticated() {
            if (this.getToken()) return true;
            return false;
        }
    };

    Object.defineProperties(Vue.prototype, {
        $auth: {
            get() {
                return Vue.auth;
            }
        }
    });
}