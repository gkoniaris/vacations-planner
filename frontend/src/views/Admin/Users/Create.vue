<template>
    <div class="home">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page">Employees</li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>

        <div class="row no-gutters">
            <div class="col-md-12">
                <form class="card left-card">
                    <div class="card-header">
                        General Info
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>First name</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    <input v-model="user.first_name" type="text" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Last name</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    <input v-model="user.last_name" type="text" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    <input v-model="user.email" type="email" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Profession</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                                    <select v-model="user.role" class="form-control">
                                        <option value="1">Software Engineer</option>
                                        <option value="2">Qa Engineer</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Gender</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-female" aria-hidden="true"></i>
                                    </span>
                                    <select v-model="user.role" class="form-control">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Total PTO per year</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-thermometer-full" aria-hidden="true"></i>
                                    </span>
                                    <input type="number" class="form-control" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Role</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-sitemap" aria-hidden="true"></i>
                                    </span>
                                    <select v-model="user.role" class="form-control">
                                        <option value="employee">Employee</option>
                                        <option value="supervisor">Supervisor</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Hired at</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <input type="date" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="btn btn-primary mt-3" @click="createEmployee()">
                            Save employee
                        </div>
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
    createEmployee() {
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

<style>

</style>