import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useOrdersStore } from "./orders.js"

export const useUserStore = defineStore('user', () => {
    const ordersStore = useOrdersStore()
    const axios = inject('axios')
    const serverBaseUrl = inject('serverBaseUrl')
    
    const user = ref(null)
    
    const userPhotoUrl = computed(() => {
        if (!user.value?.photo_url) {
            return avatarNoneUrl
        }
        return serverBaseUrl + '/storage/fotos/' + user.value.photo_url
    })
    
    const userId = computed(() => {
        return user.value?.id ?? -1
    })

    async function loadUser () {
        try {
            const response = await axios.get('users/me')
            console.log("response from users/me get: " + response.data)
            user.value = response.data
            console.log()
           // console.log("loadUser: " + response.data.data)
        } catch (error) {
            console.log("error from loadUser: " + error.message)
            clearUser () 
            throw error
        }
    }
    
    function clearUser () {
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        user.value = null
    }  
    
    async function login (credentials) {
        try {
            const response = await axios.post('login', credentials)
            console.log("response from login post: " + response.data.access_token)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await loadUser()
            return true       
        } 
        catch(error) {
            clearUser()
            console.log("error from login in store: " + error.message)
            //projectsStore.clearProjects()
            return false
        }
    }

    /* 
        async login(context, credentials) {
      try {
        let response = await axios.post("login", credentials)
        axios.defaults.headers.common.Authorization =
          "Bearer " + response.data.access_token
        sessionStorage.setItem("token", response.data.access_token)
      } catch (error) {
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem("token")
        context.commit("resetUser", null)
        throw error
      }
      await context.dispatch("refresh")
    },
    
    
    
    
    */

    async function register (credentials) {
        try {
            const response = await axios.post('register', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)  
            return true       
        } 
        catch(error) {
            clearUser()
            //projectsStore.clearProjects()
            return false
        }
    }
    
    async function logout () {
        try {
            await axios.post('logout')
            clearUser()
            //projectsStore.clearProjects()
            return true
        } catch (error) {
            return false
        }
    }

    async function restoreToken () {
        let storedToken = sessionStorage.getItem('token')
        if (storedToken) {
            axios.defaults.headers.common.Authorization = "Bearer " + storedToken
            await loadUser()
            await ordersStore.loadAllOrders()
            return true
        }
        clearUser()
        return false
    }
    
    return { user, userId, userPhotoUrl, login, register, loadUser, logout, restoreToken }
})
