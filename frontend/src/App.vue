<template>
  <div id="app" v-bind:class="{'is-logged': isLogged}">
    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="/"><i class="fa fa-plane pr-3" aria-hidden="true"></i> Vacation planner</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <!-- <li class="nav-item active">
              <router-link class="nav-link" to="/admin/users" v-if="role === 'supervisor'">Users</router-link>
            </li> -->
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
    <div class="left-navbar" v-if="isLogged">
        <router-link class="item" to="/admin/users" v-if="role === 'supervisor'">
            <i class="fa fa-users pr-4" aria-hidden="true"></i> Employees <i class="fa fa-chevron-right item-arrow" aria-hidden="true"></i>
        </router-link>
        <a class="item">
            <i class="fa fa-calendar pr-4" aria-hidden="true"></i> Calendar <i class="fa fa-chevron-right item-arrow" aria-hidden="true"></i>
        </a>
    </div>
    <div class="main-body">
      <router-view/>
    </div>
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
  .left-navbar {
    position: fixed;
    top: 56px;
    left: 0;
    z-index: 1000;
    background: #2d2d2d;
    width: 300px;
    height: 100%;
  }
  .left-navbar .item {
    padding-left: 35px;
    margin-top: 40px;
    padding-bottom: 10px;
    font-weight: bold;
    color: white;
    position: relative;
    display: block;
    cursor: pointer;
  }
  .left-navbar .item.router-link-active {
    /* color: #3e3e3e; */
  }
  .left-navbar .item-arrow {
    position: absolute;
    right: 20px;
    top: 5px;
  }
  .main-body {
    float: left;
    width: 100%;
    margin-top: 90px;
    padding: 0 50px;
  }

  .is-logged .main-body {  
    margin-left: 300px;
    width: calc(100% - 300px);
  }
</style>
