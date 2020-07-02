import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'awesome-notifications/dist/style.css'
import Vue from 'vue'
import App from './App.vue'
import router from './router'
Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
