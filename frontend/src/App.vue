<template>
  <div
    id="app"
    v-bind:class="{'is-logged': isLogged}"
  >
    <nav
      v-if="role === 'supervisor' || role === 'employee'"
      class="navbar navbar-expand-lg bg-black"
    >
      <div class="container-fluid">
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <a
          class="navbar-brand text-white mt-2"
          href="/"
        >
          <h4>Vacation planner</h4>
        </a>
        <div
          class="collapse navbar-collapse"
          id="navbarSupportedContent"
        >
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <router-link
                class="nav-link"
                to="/admin"
                v-if="role === 'supervisor'"
              >Dashboard</router-link>
            </li>
            <li class="nav-item active">
              <router-link
                class="nav-link"
                to="/employee/applications"
                v-if="role === 'employee'"
              >Applications</router-link>
            </li>
          </ul>
          <ul class="navbar-nav mr-right">
            <li class="nav-item">
              <router-link
                class="nav-link"
                to="/"
                v-if="!isLogged"
              >Login</router-link>
            </li>
            <li class="nav-item">
              <div
                class="nav-link cursor-pointer"
                @click="logout()"
                v-if="isLogged"
              >Logout</div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="main-body container-fluid">
      <div class="row h-100 no-gutters">
        <div
          class="col-xl-2 col-md-3 bg-dark h-100 text-center text-white"
          v-if="isLogged"
        >
          <div class="profile-container pt-4 pb-4">
            <div class="mt-4 profile-picture">
              <img src="./assets/images/avatar.png" />
            </div>
            <div class="welcome-back">
              <h5 class="mt-5 mb-3">Welcome back, {{user.name}}</h5>
            </div>
          </div>
          <div class="row text-center">
            <router-link
              class="col-md-12 mt-4 mb-4 text-white"
              to="/admin/users"
              v-if="role === 'supervisor'"
            >
              <i
                class="fa fa-dashboard pr-4"
                aria-hidden="true"
              ></i> Dashboard <i
                class="fa fa-chevron-right item-arrow"
                aria-hidden="true"
              ></i>
            </router-link>
            <a class="col-md-12 mt-4 mb-4 text-white">
              <i
                class="fa fa-calendar pr-4"
                aria-hidden="true"
              ></i> Calendar <i
                class="fa fa-chevron-right item-arrow"
                aria-hidden="true"
              ></i>
            </a>
          </div>
        </div>
        <router-view
          :class="{'col-xl-10 col-md-9': isLogged, 'col-xl-12': !isLogged}"
          class="col-xl-10 col-md-9 pl-3 pr-3 pt-5"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { EventBus } from './eventbus'

export default {
  name: 'App',
  components: {

  },
  data () {
    return {
      user: {
        name: 'Liza'
      },
      isLogged: false,
      role: null
    }
  },
  mounted () {
    this.init()
    EventBus.$on('auth-updated', this.init)
  },

  methods: {
    init () {
      this.isLogged = localStorage.getItem('isLogged') === 'true'
      this.role = localStorage.getItem('role')
    },
    logout () {
      localStorage.removeItem('isLogged')
      localStorage.removeItem('role')

      this.init()

      this.$router.push('/')
    }
  }
}
</script>

<style lang="scss">
@import "@/assets/css/app.scss";
</style>