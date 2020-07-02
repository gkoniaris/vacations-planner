<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="/">Vacation planner</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <router-link class="nav-link" to="/admin/users" v-if="role === 'supervisor'">Users</router-link>
            </li>
            <li class="nav-item active">
              <router-link class="nav-link" to="/employee/applications" v-if="role === 'employee'">Applications</router-link>
            </li>
          </ul>
          <ul class="navbar-nav mr-right">
            <li class="nav-item">
              <router-link class="nav-link" to="/" v-if="!isLogged">Login</router-link>
            </li>
            <li class="nav-item">
              <div class="nav-link cursor-pointer" @click="logout()" v-if="isLogged">Logout</div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <router-view class="mt-5 container"/>
  </div>
</template>

<script>
import {EventBus} from './eventbus'

export default {
  name: 'App',
  components: {

  },
  data() {
    return {
      isLogged: false,
      role: null
    }
  },
  mounted() {
    this.init()
    EventBus.$on('auth-updated', this.init)
  },

  methods: {
    init() {
      this.isLogged = localStorage.getItem('isLogged') === 'true'
      this.role = localStorage.getItem('role')
    },
    logout() {
      localStorage.removeItem('isLogged')
      localStorage.removeItem('role')

      this.init()
      
      this.$router.push('/')
    }
  }
}
</script>

<style>
  .cursor-pointer {
    cursor: pointer;
  }
</style>
