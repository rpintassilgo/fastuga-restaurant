<script setup>
  import { ref, watch, computed, onMounted, inject} from 'vue'
  import { useRouter, onBeforeRouteLeave } from 'vue-router'  
  import { useUserStore } from "../../stores/user.js"
  import { useProjectsStore } from "../../stores/projects.js"

  import ProjectDetail from "./ProjectDetail.vue"

  const router = useRouter()  
  const axios = inject('axios')
  const toast = inject('toast')
  const userStore = useUserStore()
  const projectsStore = useProjectsStore()
  

  const newProject = () => { 
    return {
      id: null,
      name: '',
      responsible_id: userStore.userId,  
      status: 'P',
      preview_start_date: null,
      preview_end_date: null,
      real_start_date: null,
      real_end_date: null,
      total_hours: null,
      billed: false,
      total_price: null,
    }
  }

  let originalValueStr = ''
  const loadProject = (id) => {
      originalValueStr = ''
      errors.value = null
      if (!id || (id < 0)) {
        project.value = newProject()
        originalValueStr = dataAsString()
      } else {
        axios.get('projects/' + id)
          .then((response) => {
            project.value = response.data.data
            originalValueStr = dataAsString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
    }

  /* Change this function */
  const save = () => {
      errors.value = null
      if (operation.value == 'insert') {
        projectsStore.insertProject(project.value)
          .then((insertedProject) => {
            project.value = insertedProject
            originalValueStr = dataAsString()
            toast.success('Project #' + project.value.id + ' was created successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Project was not created due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Project was not created due to unknown server error!')
            }
          })
      } else {
        projectsStore.updateProject(project.value)
        .then((updatedProject) => {
            project.value = updatedProject
            originalValueStr = dataAsString()
            toast.success('Project #' + project.value.id + ' was updated successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Project #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Project #' + props.id + ' was not updated due to unknown server error!')
            }
          })
      }
    }

  const cancel = () => {
    originalValueStr = dataAsString()
    router.back()
  }

  const dataAsString = () => {
      return JSON.stringify(project.value)
  }

  let nextCallBack = null
  const leaveConfirmed = () => {
      if (nextCallBack) {
        nextCallBack()
      }
  }

  onBeforeRouteLeave((to, from, next) => {
    nextCallBack = null
    let newValueStr = dataAsString()
    if (originalValueStr != newValueStr) {
      nextCallBack = next
      confirmationLeaveDialog.value.show()
    } else {
      next()
    }
  })  

  const props = defineProps({
      id: {
        type: Number,
        default: null
      }
    })

  const project = ref(newProject())  
  const users = ref([])  
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)

  const operation = computed(() => {
    return (!props.id || props.id < 0) ? 'insert' : 'update'
  })

  watch(
    () => props.id, 
    (newValue) => {
          loadProject(newValue)
    }, {
      immediate: true,
    }
  )

  onMounted (() => {
    users.value = []
    axios.get('users')
      .then((response) => {
        users.value = response.data.data
      })
      .catch((error) => {
        console.log(error)
      })
  })
</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  

  <ProjectDetail
    :operationType="operation"
    :project="project"
    :users="users"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></ProjectDetail>
</template>
