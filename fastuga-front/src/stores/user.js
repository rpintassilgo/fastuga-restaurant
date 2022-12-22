import { ref, computed, inject, watch } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useOrdersStore } from "./orders.js"
import { Toast } from 'bootstrap'

export const useUserStore = defineStore('user', () => {
    const ordersStore = useOrdersStore()
    const axios = inject('axios')
    const socket = inject('socket')
    const toast = inject('toast')
    const serverBaseUrl = inject('serverBaseUrl')
    
    const user = ref(null)
    const cart = ref([])
    
    const userPhotoUrl = computed(() => {
        if (!user.value?.photo_url) {
            return avatarNoneUrl
        }
        return serverBaseUrl + '/storage/fotos/' + user.value.photo_url
    })
    
    const userId = computed(() => {
        return user.value?.id ?? -1
    })

    const replacerFunc = () => {
        const visited = new WeakSet();
        return (key, value) => {
          if (typeof value === "object" && value !== null) {
            if (visited.has(value)) {
              return;
            }
            visited.add(value);
          }
          return value;
        };
      };
     
      

    const loadCartFromLocalStorage = () => {
        //console.log("user id do carrinho: " + JSON.stringify(userId, replacerFunc()))
        console.log("id msm do user.id: " + userId.value)
        return localStorage.getItem(userId.value) != null ? JSON.parse(localStorage.getItem(userId.value)) : []
    }

    const addProductToCart = (product) =>{
        cart.value = loadCartFromLocalStorage()
        cart.value.push(product)
        localStorage.setItem(userId.value,JSON.stringify(cart.value))
    }

    const removeProductFromCart = (product) =>{
        cart.value = loadCartFromLocalStorage()
        console.log("cart before remove: " + JSON.stringify(cart.value))
        console.log("product that im trying to remove: " + JSON.stringify(product.id))
        let r = cart.value.splice(cart.value.findIndex((p) => p.id == product.id),1)
        console.log("cart after remove: " + JSON.stringify(cart.value))
        if(r.length != 0) localStorage.setItem(userId.value,JSON.stringify(cart.value))
    }

    const emptyCart = () => { // this one is not being used rn
        cart.value = []
        localStorage.clear()
    }

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

    async function myself(){
        try {
            const response = await axios.get('users/me')
            console.log("logged user: " + JSON.stringify(response.data.data)) 
            return response.data.data
           // console.log("loadUser: " + response.data.data)
        } catch (error) {
            console.log("error from myself: " + error.message)
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
            //socket.emit('loggedIn', user.value)
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
            //socket.emit('loggedOut', user.value)
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
            //socket.emit('loggedIn', user.value)
            //await ordersStore.loadAllOrders()
            return true
        }
        clearUser()
        return false
    }

    async function changePassword($id,credentials){
        // handle error on function call
        await axios.put('users/'+$id+'/password',credentials)
    }
/*
    socket.on('updateUser', (updateUser) => {
        console.log('Someone else has updated the user #' + updateUser.id)
        if(user.value?.id == updateUser.id){
            user.value = updateUser
            toast.info(`Your user profile has changed!`)
        } else{
            toast.info(`User profile #${updateUser.id} (${updateUser.name}) has changed!`)
        }
    })
    */
    return { user, userId, userPhotoUrl, login, signUpCustomer, loadCartFromLocalStorage, addProductToCart, 
        removeProductFromCart, emptyCart, loadUser, logout, restoreToken, changePassword }
})
