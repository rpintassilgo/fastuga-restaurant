<script setup>
  import { ref, watch, computed, onMounted, inject } from 'vue'
  import { useRouter, onBeforeRouteLeave } from 'vue-router'  
  import { useUserStore } from "../../stores/user.js"
  import { useProjectsStore } from "../../stores/projects.js"
  
  import TaskDetail from "./TaskDetail.vue"

  const router = useRouter()  
  const axios = inject('axios')
  const toast = inject('toast')
  const userStore = useUserStore()
  const projectsStore = useProjectsStore()

  const newTask = () => {
      return {
        id: null,
        owner_id: userStore.userId,  
        project_id: null,
        completed: false,
        description: '',
        notes: '',
        total_hours: null
      }
  }

  const task = ref(newTask())
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)

  let originalValueStr = ''
  const loadTask = (id) => {
      originalValueStr = ''
      errors.value = null
      if (!id || (id < 0)) {
        task.value = newTask()
        originalValueStr = dataAsString()
      } else {
        axios.get('tasks/' + id)
          .then((response) => {
            task.value = response.data.data
            originalValueStr = dataAsString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
  }

  const save = () => {
      errors.value = null
      if (operation.value == 'insert') {
        axios.post('tasks', task.value)
          .then((response) => {
            task.value = response.data.data
            originalValueStr = dataAsString()
            toast.success('Task #' + task.value.id + ' was created successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Task was not created due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Task was not created due to unknown server error!')
            }
          })
      } else {
        axios.put('tasks/' + props.id, task.value)
          .then((response) => {
            task.value = response.data.data
            originalValueStr = dataAsString()
            toast.success('Task #' + task.value.id + ' was updated successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Task #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Task #' + props.id + ' was not updated due to unknown server error!')
            }
          })
      }
    }
    
  const cancel = () => {
    originalValueStr = dataAsString()
    router.back()
  }

  const dataAsString = () => {
      return JSON.stringify(task.value)
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
    },
    fixedProject: {
      type: Number,
      default: null
    }
  })

  const operation = computed( () => (!props.id || props.id < 0) ? 'insert' : 'update')
  
    // beforeRouteUpdate was not fired correctly
    // Used this watcher instead to update the ID
  watch(
    () => props.id,
    (newValue) => {
        loadTask(newValue)
      }, 
    { immediate: true}
  )
</script>


<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  
  <task-detail
    :operationType="operation"
    :task="task"
    :errors="errors"
    :projects="projectsStore.projects"
    :fixedProject="fixedProject"
    @save="save"
    @cancel="cancel"
  ></task-detail>
</template>