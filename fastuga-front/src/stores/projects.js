import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useUserStore } from "./user.js"

export const useProjectsStore = defineStore('projects', () => {
    const userStore = useUserStore()

    const axios = inject('axios')
    
    const projects = ref([])

    const totalProjects = computed(() => {
        return projects.value.length
    })

    const myInprogressProjects = computed(() => {        
        return projects.value.filter( prj => 
            prj.status == 'W' && prj.responsible_id == userStore.userId)
    })

    const totalMyInprogressProjects = computed(() => {        
        return myInprogressProjects.value.length
    })

    function getProjectsByFilter(responsibleId, status) {
        return projects.value.filter( prj =>
            (!responsibleId || responsibleId == prj.responsible_id) &&
            (!status || status == prj.status)
        )
    }
    
    function getProjectsByFilterTotal(responsibleId, status) {
        return getProjectsByFilter(responsibleId, status).length
    }

    function clearProjects() {
        projects.value = []
    }

    async function loadProjects() {
        try {
            const response = await axios.get('projects')
            projects.value = response.data.data
            return projects.value
        } catch (error) {
            clearProjects()
            throw error
        }
    }
    
    async function insertProject(newProject) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the insertProject
        const response = await axios.post('projects', newProject)
        projects.value.push(response.data.data)
        return response.data.data
    }

    async function updateProject(updateProject) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the updateProject
        const response = await axios.put('projects/' + updateProject.id, updateProject)
        let idx = projects.value.findIndex((t) => t.id === response.data.data.id)
        if (idx >= 0) {
            projects.value[idx] = response.data.data
        }
        return response.data.data
    }

    async function deleteProject( deleteProject) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the deleteProject
        const response = await axios.delete('projects/' + deleteProject.id)
        let idx = projects.value.findIndex((t) => t.id === response.data.data.id)
        if (idx >= 0) {
            projects.value.splice(idx, 1)
        }
        return response.data.data
    }  
    
    return { projects, totalProjects, myInprogressProjects, totalMyInprogressProjects, 
        getProjectsByFilter, getProjectsByFilterTotal, 
        loadProjects, clearProjects, insertProject, updateProject, deleteProject}
})
