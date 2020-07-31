<template>
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Employees</li>
                </ol>
            </nav>
            <router-link to="/admin/users/create" class="btn btn-primary btn-sm mb-3 float-right mt-2">New Employee</router-link>

            <table class="table table-hover">
                <thead class="thead-dark">
                    <th>
                        Full name
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Role
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
                            <router-link :to="'/admin/users/' + employee.id" class="btn btn-primary btn-sm">Edit</router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
