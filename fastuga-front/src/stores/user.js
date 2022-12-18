import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useOrdersStore } from "./orders.js"

export const useUserStore = defineStore('user', () => {
    const ordersStore = useOrdersStore()
    const axios = inject('axios')
    const socket = inject("socket")
    const serverBaseUrl = inject('serverBaseUrl')
    
    const user = ref(null)
    
    const userPhotoUrl = computed(() => {
        if (!user.value?.photo_url) {
            //console.log(!user.value?.photo_url);
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
            console.log("logged user: " + JSON.stringify(response.data.data))
            user.value = response.data.data
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
            socket.emit('loggedIn', user.value)
            return true       
        } 
        catch(error) {
            clearUser()
            console.log("error from login in store: " + error.message)
            //projectsStore.clearProjects()
            return false
        }
    }


    async function signUpCustomer (credentials) {
        try {
           // console.log("")
            const response = await axios.post('customers', credentials)
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
            socket.emit('loggedOut', user.value)
            clearUser()
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
            socket.emit('loggedIn', user.value)
            return true
        }
        clearUser()
        return false
    }

    socket.on('updateUser', (updatedUser) => {
        console.log('Someone else has updated the user #' + updatedUser.id)
        if (user.value?.id == updatedUser.id) {
            user.value = updatedUser
            toast.info('Your user profile has been changed!')
        } else {
            toast.info(`User profile #${updatedUser.id} (${updatedUser.name}) has changed!`)
        }
    })  
    
    return { user, userId, userPhotoUrl, login, signUpCustomer, loadUser, logout, restoreToken }
})
