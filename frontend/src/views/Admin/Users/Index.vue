<template>
    <div class="home">
        <h2 class="mb-3">
            Company users
            <router-link to="/admin/users/create" class="btn btn-primary btn-sm float-right mt-2">New User</router-link>
        </h2>

        <table class="table table-hover table-sm">
            <thead>
                <th>
                    Full name
                </th>
                <th>
                    Email
                </th>
                <th>
                    Type
                </th>
                <th>
                    Actions
                </th>
            </thead>
            <tbody>
                <tr v-bind:key="employee.id" v-for="employee in employees">
                    <td>
                        {{employee.first_name}} {{employee.last_name}}
                    </td>
                    <td>
                        {{employee.email}}
                    </td>
                    <td>
                        {{employee.role === 'employee' ? 'Employee' : 'Supervisor'}}
                    </td>
                    <td>
                        <router-link :to="'/admin/users/' + employee.id" class="btn btn-primary">Edit</router-link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

import http from '../../../http'

export default {
  name: 'UsersIndex',
  data() {
      return {
          employees: []
      }
  },
  mounted() {
      this.getUsers()
  },
  methods: {
    getUsers() {
        http.get('/api/users', {withCredentials: true})
        .then(response => {
            this.employees = response.data
        })
    }
  }
}
</script>
