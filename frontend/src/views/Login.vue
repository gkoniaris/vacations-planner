<template>
  <div class="row login-page">
    <div class="col-xl-8 offset-xl-2 mt-xl-5 d-flex justify-content-center align-items-center">
      <div class="card d-flex ">
        <div class="row no-gutters d-flex">
          <div class="login-image d-flex">
          </div>
          <div class="d-flex">
            <div class="card-block px-2 text-center mt-3 pr-5 pl-5">
              <h4 class="card-title">WELCOME BACK</h4>
              <form>
                <div class="form-group">
                  <input
                    placeholder="Enter your email"
                    v-model="email"
                    class="form-control"
                    type="email"
                  />
                </div>
                <div class="form-group">
                  <input
                    placeholder="Enter your password"
                    v-model="password"
                    class="form-control"
                    type="password"
                  />
                </div>
                <div
                  class="btn btn-primary btn-block float-right"
                  @click="login()"
                >LOGIN</div>
                <div>Don't have an account yet? <router-link to="/register">Register now</router-link>.</div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import http from '../http'
import notifier from '../notifier'
import { EventBus } from '../eventbus'

export default {
  name: 'Login',
  components: {

  },
  data () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login () {
      http.post('/api/login', {
        email: this.email,
        password: this.password
      }, { withCredentials: true })
        .then(response => {
          localStorage.setItem('isLogged', 'true')
          localStorage.setItem('role', response.data.user.role)

          notifier.success('You have successfully logged in')

          EventBus.$emit('auth-updated')

          if (response.data.user.role === 'supervisor') {
            this.$router.push('/admin/users')
          } else {
            this.$router.push('/employee/applications')
          }
        })
        .catch((err) => {
          notifier.alert(err.response.data.message)
        })
    }
  }
}
</script>

<style scoped>
.form-control {
  border-radius: 100px !important;
  background: white !important;
}
/* .login-page {
  margin: 0;
  position: absolute;
  left: 10%;
  top: 50%;
  width: 80%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
} */
.login-image {
  background-image: url("../assets/images/login-image.jpg");
  background-position: center;
  background-size: cover;
}
.form-control {
  padding: 30px 20px;
  margin-bottom: 40px;
}
.btn {
  border-radius: 100px;
  padding: 20px;
  margin-top: 10px;
  margin-bottom: 20px;
}
.card {
  margin-bottom: 40px;
}
.card.card-primary {
  height: 100%;
}
.card-title {
  margin: 60px 0;
}
.login-image {
  width: 400px;
}
@media (min-width: 1200px) {
  /* .login-page {
    margin: 0;
    position: absolute;
    left: 10%;
    top: 50%;
    width: 80%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
  } */
  .login-image {
    min-height: 500px;
  }
}
@media (max-width: 1200px) {
  .login-page {
    position: relative;
    -ms-transform: translateY(0);
    transform: translateY(0);
    margin-bottom: 50px;
  }
  .login-image {
    min-height: 300px;
  }
}
@media (max-width: 992px) {
  .login-image {
    width: 100%;
  }
  .card {
    height: 100%;
    width: 100%;
    align-items: center;
    margin-top: 0;
    border: 0;
  }
  .card * {
    width: 100%;
  }
  .login-page {
    position: relative;
    -ms-transform: translateY(0);
    transform: translateY(0);
    margin-bottom: 50px;
  }
}
</style>
