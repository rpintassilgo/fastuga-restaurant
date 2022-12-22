import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import Toaster from "@meforma/vue-toaster"
// import Pagination from "v-pagination-3"
import FieldErrorMessage from './components/global/FieldErrorMessage.vue'
import ConfirmationDialog from './components/global/ConfirmationDialog.vue'
import { io } from "socket.io-client"

import App from './App.vue'
import router from './router'


//import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css"
import "bootstrap"

const app = createApp(App)


const serverBaseUrl = import.meta.env.VITE_APP_BASE_URL
app.provide('axios', axios.create({
    baseURL: serverBaseUrl + '/api',
    headers: {
      'Content-type': 'application/json',
    },
  }))
app.provide('axiosImage', axios.create({
    baseURL: serverBaseUrl + '/api',
    headers: {
      'Content-type': 'multipart/form-data',
    },
  }))
app.provide('serverBaseUrl', serverBaseUrl)  

app.use(Toaster, {
    // Global/Default options
    position: 'top',
    timeout: 3000,
    pauseOnHover: true
})

app.provide('toast', app.config.globalProperties.$toast);

app.provide('socket', io("http://localhost:8080"))

app.use(createPinia())
app.use(router)

app.component('FieldErrorMessage', FieldErrorMessage)
app.component('ConfirmationDialog', ConfirmationDialog)
//app.component('pagination', Pagination)

app.mount('#app')
