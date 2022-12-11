import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useProjectsStore } from "./projects.js"

export const useUserStore = defineStore('user', () => {
    const projectsStore = useProjectsStore()
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
            user.value = response.data.data
        } catch (error) {
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
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await loadUser()
            //await projectsStore.loadProjects()
            return true       
        } 
        catch(error) {
            clearUser()
            //projectsStore.clearProjects()
            return false
        }
    }

    async function register (credentials) {
        try {
            const response = await axios.post('register', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)  
            return true       
        } 
        catch(error) {
            clearUser()
            projectsStore.clearProjects()
            return false
        }
    }
    
    async function logout () {
        try {
            await axios.post('logout')
            clearUser()
            projectsStore.clearProjects()
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
            await projectsStore.loadProjects()
            return true
        }
        clearUser()
        projectsStore.clearProjects()
        return false
    }
    
    return { user, userId, userPhotoUrl, login, register, logout, restoreToken }
})
