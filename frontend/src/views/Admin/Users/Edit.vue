<template>
  <div class="home">
    <h2 class="mb-3">Edit user</h2>

    <div class="row">
      <div class="col-md-4">
        <form>
          <div class="form-group">
            <label>First name</label>
            <input
              v-model="user.first_name"
              type="text"
              class="form-control"
            />
          </div>
          <div class="form-group">
            <label>Last name</label>
            <input
              v-model="user.last_name"
              type="text"
              class="form-control"
            />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input
              v-model="user.email"
              type="email"
              class="form-control"
            />
          </div>

          <div
            class="btn btn-primary btn-block"
            @click="updateUser()"
          >Update user</div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import http from '../../../http'
import notifier from '../../../notifier'

export default {
  name: 'UsersCreate',
  data () {
    return {
      user: {}
    }
  },
  mounted () {
    this.getUser(this.$route.params.id)
  },
  methods: {
    getUser (id) {
      http
        .get('/api/users?id=' + id, { withCredentials: true })
        .then((response) => {
          this.user = response.data
        })
    },
    updateUser () {
      http
        .put('/api/users', this.user, { withCredentials: true })
        .then(() => {
          notifier.success('User created successfully')
          this.user = {}
          this.$router.push('/admin/users')
        })
        .catch(err => {
          notifier.alert(err.response.data.message)
        })
    }
  }
}
</script>
