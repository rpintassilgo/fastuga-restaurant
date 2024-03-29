import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useOrdersStore } from "./orders.js"

export const useUserStore = defineStore('user', () => {
    const ordersStore = useOrdersStore()
    const axios = inject('axios')
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

    const loadCartFromLocalStorage = () => {
        return localStorage.getItem(userId.value) != null ? JSON.parse(localStorage.getItem(userId.value)) : []
    }

    const addProductToCart = (product) =>{
        cart.value = loadCartFromLocalStorage()
        cart.value.push(product)
        localStorage.setItem(userId.value,JSON.stringify(cart.value))
    }

    const removeProductFromCart = (product) =>{
        cart.value = loadCartFromLocalStorage()
        let r = cart.value.splice(cart.value.findIndex((p) => p.id == product.id),1)
        if(r.length != 0) localStorage.setItem(userId.value,JSON.stringify(cart.value))
    }

    async function loadUser () {
        try {
            const response = await axios.get('users/me')
            user.value = response.data.data
        } catch (error) {
            console.log(error.message)
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
            console.log(error.message)
            return false
        }
    }


    async function signUpCustomer (credentials) {
        try {
            const response = await axios.post('customers', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)  
            return true       
        } 
        catch(error) {
            clearUser()
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
            return true
        }
        clearUser()
        return false
    }

    async function changePassword($id,credentials){
        // handle error on function call
        await axios.put('users/'+$id+'/password',credentials)
    }
    
    return { user, userId, userPhotoUrl, login, signUpCustomer, loadCartFromLocalStorage, addProductToCart, 
        removeProductFromCart, loadUser, logout, restoreToken, changePassword }
})
