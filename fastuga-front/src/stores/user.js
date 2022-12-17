import { ref, computed, inject, watch } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useOrdersStore } from "./orders.js"

export const useUserStore = defineStore('user', () => {
    const ordersStore = useOrdersStore()
    const axios = inject('axios')
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

    const loadCartFromLocalStorage = () => {
        return localStorage.getItem(userId) != null ? JSON.parse(localStorage.getItem(userId)) : []
    }

    const addProductToCart = (product) =>{
        cart.value = loadCartFromLocalStorage()
        cart.value.push(product)
        localStorage.setItem(userId,JSON.stringify(cart.value))
    }

    const removeProductFromCart = (product) =>{
        cart.value = loadCartFromLocalStorage()
        console.log("cart before remove: " + JSON.stringify(cart.value))
        console.log("product that im trying to remove: " + JSON.stringify(product.id))
        let r = cart.value.splice(cart.value.findIndex((p) => p.id == product.id),1)
        console.log("cart after remove: " + JSON.stringify(cart.value))
        if(r.length != 0) localStorage.setItem(userId,JSON.stringify(cart.value))
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
            return true
        }
        clearUser()
        return false
    }
    
    return { user, userId, userPhotoUrl, login, signUpCustomer, loadCartFromLocalStorage, addProductToCart, removeProductFromCart, emptyCart, loadUser, logout, restoreToken }
})
