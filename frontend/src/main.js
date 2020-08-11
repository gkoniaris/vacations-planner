import 'bootstrap'
import 'bootstrap/scss/bootstrap.scss'
import 'awesome-notifications/dist/style.css'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import App from './App.vue'
import router from './router'

Vue.config.productionTip = false

Vue.use(Vuelidate)

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')