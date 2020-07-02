<template>
    <div class="home">
        <h2 class="mb-3">
            My vacation applications
            <router-link to="/employee/applications/create" class="btn btn-primary btn-sm float-right mt-2">Submit request</router-link>
        </h2>

        <table class="table table-striped table-sm">
            <thead>
                <th>
                    Date submitted
                </th>
                <th>
                    Dates requested
                </th>
                <th>
                    Days requested
                </th>
                <th>
                    Status
                </th>
            </thead>
            <tbody>
                <tr v-bind:key="application.id" v-for="application in applications">
                    <td>
                        {{application.created_at}}
                    </td>
                    <td>
                        {{application.from}} to {{application.to}}
                    </td>
                    <td>
                        {{application.days_requested}}
                    </td>
                    <td>
                        {{application.status}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

import http from '../../../http'

export default {
  name: 'Applications',
  data() {
      return {
          applications: []
      }
  },
  mounted() {
    this.getApplications()
  },
  methods: {
    getApplications() {
        http.get('/api/applications', {withCredentials: true})
        .then(response => {
            this.applications = response.data
        })
    }
  }
}
</script>
