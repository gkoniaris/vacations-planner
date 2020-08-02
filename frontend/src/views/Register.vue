<template>
  <div class="row login-page">
    <div class="col-xl-6 offset-xl-3 mt-5">
        <div class="card card-primary">
            <div class="card-header bg-primary text-center pt-4 pb-4">
                <div class="steps-container">
                    <div class="step-container">
                        <div class="step" v-bind:class="{finished: step > 0}">
                            <i class="fa fa-address-book"></i>
                        </div>
                    </div>

                    <div class="step-container">
                        <div class="step" v-bind:class="{finished: step > 1}">
                            <i class="fa fa-industry"></i>
                        </div>
                    </div>

                    <div class="step-container last">
                        <div class="step" v-bind:class="{finished: step > 2}">
                            <i class="fa fa-question"></i>
                        </div>
                    </div>
                </div>
            </div>
            <form class="card-body">
                <div class="row">
                    <div ref="userform" class="col-xl-8 offset-xl-2" v-show="step === 1">
                        <h5 class="mt-3 mb-3">Let's get to know each other first</h5>
                        <div class="pt-3">
                            <div class="form-group" >
                                <label>Email</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    </div>
                                    <input 
                                        v-model="$v.form.user.email.$model" 
                                        type="email"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.user.email.$error }"
                                        placeholder="Enter your email"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.user.email.$error">Please enter a valid email.</div>
                            </div>
                            <div class="form-group">
                                <label>First name</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>
                                    <input 
                                        v-model="$v.form.user.first_name.$model" 
                                        type="text"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.user.first_name.$error }"
                                        placeholder="Enter your first name"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.user.first_name.$error">Please enter a valid first name.</div>
                            </div>
                            <div class="form-group">
                                <label>Last name</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>
                                    <input 
                                        v-model="$v.form.user.last_name.$model" 
                                        type="text"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.user.last_name.$error }"
                                        placeholder="Enter your last name"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.user.last_name.$error">Please enter a valid last name.</div>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                    <input 
                                        v-model="$v.form.user.password.$model" 
                                        type="password"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.user.password.$error }"
                                        placeholder="Enter your password"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.user.password.$error">Please enter a valid password.</div>
                            </div>
                            <div class="form-group">
                                <label>Password confirmation</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                    <input 
                                        v-model="$v.form.user.password_confirmation.$model" 
                                        type="password"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.user.password_confirmation.$error }"
                                        placeholder="Enter your password confirmation"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.user.password_confirmation.$error">Password and password confirmation don't match.</div>
                            </div>
                            <div class="btn btn-primary float-right mt-5" @click="nextStep()">Next step <i class="pl-2 fa fa-arrow-right"/></div>
                        </div>
                    </div>
                    <div ref="company form" class="col-xl-8 offset-xl-2" v-if="step === 2">
                        <h5 class="mt-3 mb-3">Tell us a bit more about your company</h5>
                        <div class="form-form pt-3">
                            <div class="form-group">
                                <label>Company's name</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input 
                                        v-model="$v.form.company.name.$model" 
                                        type="email"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.company.name.$error }"
                                        placeholder="Enter your company's name"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.name.$error">Please enter a valid company name.</div>
                            </div>
                            <div class="form-group">
                                <label>Industry</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-industry" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <select 
                                        v-model="$v.form.company.industry_id.$model" 
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.company.industry_id.$error }"
                                        placeholder="Select an industry"
                                    >
                                        <option value="-1" disabled selected>Select an industry</option>
                                        <option :key="industry.id" v-for="industry in industries" :value="industry.id">{{industry.name}}</option>
                                    </select>
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.industry_id.$error">Please select a valid industry.</div>
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-globe" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input 
                                        v-model="$v.form.company.country.$model" 
                                        type="email"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.company.country.$error }"
                                        placeholder="Enter your company's name"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.country.$error">Please enter a valid country.</div>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input 
                                        v-model="$v.form.company.city.$model" 
                                        type="email"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.company.city.$error }"
                                        placeholder="Enter your company's name"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.city.$error">Please enter a valid city.</div>
                            </div>
                            <div class="form-group">
                                <label>Vat</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <input 
                                        v-model="$v.form.company.vat.$model" 
                                        type="email"
                                        class="form-control" 
                                        :class="{ 'is-invalid': submitted && $v.form.company.vat.$error }"
                                        placeholder="Enter your company's name"
                                    >
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.vat.$error">Please enter a valid VAT.</div>
                            </div>
                            <div class="form-group pt-2">
                                <label>Total employees</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <select 
                                        v-model="$v.form.company.total_employees.$model" 
                                        class="form-control"
                                        :class="{ 'is-invalid': submitted && $v.form.company.total_employees.$error }"
                                    >
                                        <option selected disabled=true value="-1">How many employees does your company employ?</option>
                                        <option value="1 - 9">1 - 9</option>
                                        <option value="10 - 50">10 - 50</option>
                                        <option value="51 - 200">51 - 200</option>
                                        <option value="201 - 1000">201 - 1000</option>
                                        <option value="1000+">1000+</option>
                                    </select>
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.total_employees.$error">Please select how many employees your company has.</div>
                            </div>
                            <div class="btn btn-primary float-left mt-5" @click="previousStep()"><i class="pr-2 fa fa-arrow-left"/> Previous step</div>
                            <div class="btn btn-primary float-right mt-5" @click="nextStep()">Next step <i class="pl-2 fa fa-arrow-right"/></div>
                        </div>
                    </div>
                    <div ref="company form" class="col-xl-8 offset-xl-2" v-if="step === 3">
                        <h5 class="mt-3 mb-3">Your feedback is valuable</h5>
                        <div class="form-form pt-3">
                            <div class="form-group">
                                <label>Where did you hear about vacation planner?</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-addon">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <select 
                                        v-model="feedback.from" 
                                        class="form-control"
                                        :class="{ 'is-invalid': submitted && $v.form.company.total_employees.$error }"
                                    >
                                        <option selected disabled=true value="-1">Select a source</option>
                                        <option value="github">Github</option>
                                        <option value="linkedin">Linkedin</option>
                                        <option value="linkedin">Dev.to</option>
                                        <option value="linkedin">My blog</option>
                                        <option value="linkedin">Other</option>
                                    </select>
                                </div>
                                <div class="error" v-if="submitted && $v.form.company.name.$error">Please enter a valid company name.</div>
                            </div>
                            <div class="btn btn-primary float-left mt-5" @click="previousStep()"><i class="pr-2 fa fa-arrow-left"/> Previous step</div>
                            <div class="btn btn-success float-right mt-5" @click="register()">Finish registration</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>   
    </div>
  </div>
</template>

<script>
import http from '../http'
import notifier from '../notifier'
// import {EventBus} from '../eventbus'
import { required, email, minValue } from 'vuelidate/lib/validators'

export default {
  name: 'Register',
  components: {

  },
  data() {
    return {
      step: 1,
      industries: [],
      submitted: false,
      feedback: {
          from: -1
      },
      form: {
        user: {
            email: '',
            first_name: '',
            last_name: '',
            password: '',
            password_confirmation: ''
        },
        company: {
            name: '',
            industry_id: -1,
            country: '',
            city: '',
            total_employees: -1,
            vat: ''
        }
      }
    }
  },
  validations: {
    form: {
      user: {
        email: {
            required,
            email
        },
        first_name: {
            required
        },
        last_name: {
            required
        },
        password: {
            required
        },
        password_confirmation: {
            required
        }
      },
      company: {
          name: {
              required
          },
          industry_id: {
              required,
              minValue: minValue(1)
          },
          country: {
              required
          },
          city: {
              required
          },
          total_employees: {
              required
          },
          vat: {
              required
          }
      }
    }
  },
  mounted() {
    this.getIndustries()
  },
  methods: {
    async getIndustries() {
        const industries = await http.get('/api/taxonomies/industries')

        this.industries = industries.data
    },
    nextStep() {
        if (this.step === 1) {
            this.submitted = true
            this.$v.$touch()

            const validated = !this.$v.form.user.email.$invalid 
            && !this.$v.form.user.first_name.$invalid 
            && !this.$v.form.user.last_name.$invalid 
            && !this.$v.form.user.password.$invalid 
            && !this.$v.form.user.password_confirmation.$invalid  
        
            if (!validated) {
                return false
            } 

            this.$v.$reset()
            return this.step++
        }

        if (this.step === 2) {
            this.submitted = true
            this.$v.$touch()

            const validated = !this.$v.form.company.name.$invalid 
            && !this.$v.form.company.industry_id.$invalid 
            && !this.$v.form.company.country.$invalid 
            && !this.$v.form.company.city.$invalid 
            && !this.$v.form.company.total_employees.$invalid
            && !this.$v.form.company.vat.$invalid 
        
            if (!validated) {
                return false
            } 

            this.$v.$reset()
            return this.step++
        }
    },
    previousStep() {
        this.step--
    },
    register() {
      http.post('/api/register', {
        user: this.form.user,
        company: this.form.company
      }, { withCredentials: true })
      .then(response => {
        notifier.success('You have successfully registered')
        this.$router.push('/login')
      })
      .catch((err) => {
        notifier.alert(err.response.data.message)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../assets/css/register.scss";
</style>
