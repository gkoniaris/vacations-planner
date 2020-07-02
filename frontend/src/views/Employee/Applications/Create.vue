<template>
    <div class="home">
        <h2 class="mb-3">
            Create new user
        </h2>

        <div class="row">
            <div class="col-md-4">
                <form>
                    <div class="form-group">
                        <label>Vacation start</label>
                        <datepicker format="yyyy-MM-dd" input-class="form-control" v-model="application.from" name="uniquename"></datepicker>
                    </div>
                    <div class="form-group">
                        <label>Vacation end</label>
                        <datepicker format="yyyy-MM-dd" input-class="form-control" v-model="application.to" name="uniquename"></datepicker>
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <textarea v-model="application.reason" class="form-control"/>
                    </div>

                    <div class="btn btn-primary btn-block" @click="createApplication()">
                        Create Application
                    </div>
                </form>
            </div>
        </div>
        <div class="loader" v-if="activeLoader">
            <div class="lds-dual-ring"></div>
        </div>
    </div>
</template>

<script>
const zeroPad = (num, places) => String(num).padStart(places, '0')

import http from '../../../http'
import notifier from '../../../notifier'
import Datepicker from 'vuejs-datepicker'

export default {
  name: 'ApplicationsCreate',
  components: {
      Datepicker
  },
  data() {
      return {
          application: {},
          activeLoader: false
      }
  },
  mounted() {

 },
  methods: {
    formatDate(date) {
        return date.getFullYear() + '-' + zeroPad(date.getMonth() + 1, 2) + '-' + zeroPad(date.getDate(), 2)
    },
    createApplication() {
        if (!this.application.from || !this.application.to) {
            return notifier.alert('Please select valid dates')
        }

        this.activeLoader = true

        const application = {
            from: this.formatDate(this.application.from),
            to: this.formatDate(this.application.to),
            reason: this.application.reason
        }
        
        http.post('/api/applications', application, { withCredentials: true })
        .then(() => {
            this.activeLoader = false
            notifier.success('Application created successfully')

            this.$router.push('/employee/applications')
        })
        .catch(err => {
            this.activeLoader = false

            notifier.alert(err.response.data.message)
        })
}
  }
}
</script>

<style scoped>
.vdp-datepicker > div > input[type=text] {
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    border-color: gray;
    width: 100%;
}
.loader {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 10000000;
    background: black;
    width: 100%;
    height: 100%;
    text-align: center;
    opacity: 0.4;
}
.lds-dual-ring {
  display: inline-block;
  width: 80px;
  height: 80px;
  position: absolute;
  left: 50%;
  top: 50%;
  margin-top: -40px;
  margin-left: -40px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 64px;
  height: 64px;
  margin: 8px;
  border-radius: 50%;
  border: 6px solid #fff;
  border-color: #fff transparent #fff transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>