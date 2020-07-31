<template>
    <div class="home">
        <h2 class="mb-3">
            Create new user
        </h2>

        <div class="row">
            <div class="col-md-4">
                <form>
                    <div class="form-group">
                        <label>First name</label>
                        <input v-model="user.first_name" type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Last name</label>
                        <input v-model="user.last_name" type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input v-model="user.email" type="email" class="form-control"/>
                    </div>
                    <!-- <div class="form-group">
                        <label>Password</label>
                        <input v-model="user.password" type="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input v-model="user.confirm_password" type="password" class="form-control"/>
                    </div> -->
                    <div class="form-group">
                        <label>User Type</label>
                        <select v-model="user.role" class="form-control">
                            <option value="employee">Employee</option>
                            <option value="supervisor">Supervisor</option>
                        </select>
                    </div>

                    <div class="btn btn-primary btn-block" @click="createUser()">
                        Create user
                    </div>
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
  data() {
      return {
          user: {}
      }
  },
  mounted() {

 },
  methods: {
    createUser() {
        http.post('/api/users', this.user, {withCredentials: true})
        .then(() => {
            notifier.success('User created successfully')
            this.user = {}
        })
        .catch(err => {
            notifier.alert(err.response.data.message)
        })
    }
  }
}
</script>
