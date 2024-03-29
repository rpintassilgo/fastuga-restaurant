import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { io } from 'socket.io-client'
import axios from 'axios'
import Toaster from "@meforma/vue-toaster"
import Pagination from "v-pagination-3"
import FieldErrorMessage from './components/global/FieldErrorMessage.vue'
import ConfirmationDialog from './components/global/ConfirmationDialog.vue'

import App from './App.vue'
import router from './router'

//import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css"
import "bootstrap"

const app = createApp(App)

const serverBaseUrl = import.meta.env.VITE_APP_BASE_URL
const paymentServiceUri = import.meta.env.VITE_PAYMENT_SERVICE_URI
app.provide('axios', axios.create({
    baseURL: serverBaseUrl + '/api',
    headers: {
      'Content-type': 'application/json',
    },
  }))
app.provide('axiosImage', axios.create({
    baseURL: serverBaseUrl + '/api'
  }))
app.provide('serverBaseUrl', serverBaseUrl)  
app.provide('paymentServiceUri', paymentServiceUri) 
//app.provide('socket', io(import.meta.env.VITE_APP_WS_SERVER))

app.use(Toaster, {
    // Global/Default options
    position: 'top',
    timeout: 3000,
    pauseOnHover: true
})

app.provide('toast', app.config.globalProperties.$toast);

app.use(createPinia())
app.use(router)

app.component('FieldErrorMessage', FieldErrorMessage)
app.component('ConfirmationDialog', ConfirmationDialog)
app.component('pagination', Pagination)

app.mount('#app')
