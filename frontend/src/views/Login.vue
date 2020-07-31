<template>
  <div class="row login-page">
    <div class="col-md-10 offset-1 mt-5">
        <div class="card">
          <div class="row no-gutters">
              <div class="col-md-6">
                  <img src="@/assets/images/login-image.jpg" class="img-fluid" alt="">
              </div>
              <div class="col-md-6">
                  <div class="card-block px-2 text-center mt-3 pr-5 pl-5">
                      <h4 class="card-title">WELCOME BACK</h4>
                      <form>
                      <div class="form-group">
                        <input placeholder="Enter your email" v-model="email" class="form-control" type="email" />
                      </div>
                      <div class="form-group">
                        <input placeholder="Enter your password" v-model="password" class="form-control" type="password" />
                      </div>
                      <div class="btn btn-primary btn-block float-right" @click="login()">LOGIN</div>
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
import {EventBus} from '../eventbus'

export default {
  name: 'Login',
  components: {

  },
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    login() {
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
#app {
  background: #248de6;
}
.login-page {
  display: flex;
  align-items: center;
  justify-items: center;
  position: absolute;
  top: 18%;
  left: 20%;
  width: 60%;
  height: 60%;
  overflow:hidden;

}
.form-control{
  padding: 30px 20px;
  margin-bottom: 40px;
}
.btn {
  border-radius: 100px;
  padding: 20px;
  margin-top: 30px;
}
.card-title{
  margin: 50px 0;
}
</style>
