<template>
    <div class="row dashboard">
        <div class="col-md-12">
            <!-- <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <h4>Employees</h4>
                    </li>
                </ol>
            </nav>
            <router-link to="/admin/users/create" class="btn btn-primary btn-sm mb-3 float-right mt-2">New Employee</router-link> -->

            <div class="row d-flex">
                <div class="col-lg-3 d-flex justify-content-center">
                    <div class="card text-white bg-primary mb-3 text-center w-100">
                        <div class="card-body">
                            <i class="fa fa-user fa-3 mb-3" aria-hidden="true"></i>
                            <h4 class="card-title">10 employees</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-center">
                    <div class="card text-white bg-purple mb-3 text-center w-100">
                        <div class="card-body">
                            <i class="fa fa-spinner fa-3 mb-3" aria-hidden="true"></i>

                            <h4 class="card-title">10 pending requests</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-center">
                    <div class="card text-white bg-green mb-3 text-center w-100">
                        <div class="card-body">
                            <h4 class="card-title">One new user</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 d-flex justify-content-center">
                    <div class="card text-white bg-red mb-3 text-center w-100">
                        <div class="card-body">
                            <i class="fa fa-exclamation-triangle  fa-3 mb-3" aria-hidden="true"></i>
                            <h4 class="card-title">2 requests overlap</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters mt-4">
                <div class="col-md-5">
                </div>
                <div class="col-md-7 box">
                    <h5 class="header">Your Employees</h5>
                    <div class="table-responsive">
                        <table class="table table table-hover">
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
            </div>
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

<style scoped>
.card {
    border-radius: 0;
    border: 0;
}
.card-body {
    padding: 0;
    margin-top: 3rem;
    margin-bottom: 2rem;
    border-radius: 0;
}
</style>