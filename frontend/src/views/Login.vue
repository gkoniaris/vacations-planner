<template>
  <div class="col-md-4 offset-4">
      <div class="card">
        <div class="card-header">
          <h5>Login</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="form-group">
              <label>Email</label>
              <input v-model="email" class="form-control" type="email" />
            </div>
            <div class="form-group">
              <label>Password</label>
              <input v-model="password" class="form-control" type="password" />
            </div>
            <div class="btn btn-primary btn-block float-right" @click="login()">Login</div>
          </form>
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
