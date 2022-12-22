<script setup>
  import { ref, watch, inject, computed } from 'vue'
  import UserDetail from "./UserDetail.vue"
  import { useRouter, onBeforeRouteLeave } from 'vue-router'  
  import { useUserStore } from "../../stores/user.js"
  import axios from 'axios'
  
  const router = useRouter()  
  const axios2 = inject('axios')
  const toast = inject('toast')
  const userStore = useUserStore()


  const props = defineProps({
      id: {
        type: Number,
        default: null
      }
  })

  const newUser = () => {
      return {
        id: null,
        name: '',
        email: '',
        type: '',
        blocked: null,
        photo_url: '',
        photo_file: null,
      }
  }

  const operation = computed( () => (!props.id || props.id < 0) ? 'insert' : 'update')

  let originalValueStr = ''
  const loadUser = (id) => {    
    originalValueStr = ''
      errors.value = null
      if (!id || (id < 0)) {
        user.value = newUser()
        originalValueStr = dataString()
      } else {
        axios2.get('users/' + id)
          .then((response) => {
            user.value = response.data.data
            originalValueStr = dataString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
  }

    const imageUpload = async () => {
      let url = ""
      if(user.value.photo_file != null){
            let formData = new FormData()
            formData.append('photo_file',user.value.photo_file)
            try {
                let response = await axios.post(`${import.meta.env.VITE_APP_BASE_URL}/api/users/image`,formData, 
                {
                  headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': "Bearer " + sessionStorage.getItem('token')
                  }
                })

                console.log("photo_url: " + JSON.stringify(response.data))
                url = response.data

            } catch (error) {
              console.log("erro: " + error.message)
            }
      }
      return url
  }

  const save = async () => {
      errors.value = null
      if (operation.value == 'insert') {
        // it doesnt make sense to sign up with an image so the option to create accounts with profile pics
        // will only we able to the manager employee account
        if(userStore.user.type == "EM"){
            let image = await imageUpload()
            if(image != "") user.value.photo_url = image
        }
        let postString = user.value.type == "C" ? 'customers' : 'users'
        axios2.post(postString, user.value)
          .then((response) => {
            user.value = response.data.data
            originalValueStr = dataString()
            toast.success('User #' + user.value.id + ' was created successfully.')
            if(user.value.type == "M") router.push({ name: 'Users' })
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('User was not created due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('User was not created due to unknown server error!')
            }
          })
      } else {
        let newPhoto = await imageUpload()
        console.log("newPhoto: " + newPhoto)
        if( newPhoto != ""){
          user.value.photo_url = newPhoto
          console.log("photo_url put: " + user.value.photo_url)
        }
        let putString = user.value.type == "C" ? `customers/${props.id}` : `users/${props.id}`
        axios2.put(putString, user.value)
          .then((response) => {
            user.value = response.data.data
            originalValueStr = dataString()
            toast.success('User #' + user.value.id + ' was updated successfully.')
            if(user.value.type == "M") router.push({ name: 'Users' })
          })
          .catch((error) => {
            console.log("error update: " + error.message)
            if (error.response.status == 422) {
              toast.error('User #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('User #' + props.id + ' was not updated due to unknown server error!')
            }
          })
      }
  }

  const cancel = () => {
    originalValueStr = dataString()
    router.back()
  }

  const dataString = () => {
      return JSON.stringify(user.value)
  }

  let nextCallBack = null
  const leaveConfirmed = () => {
      if (nextCallBack) {
        nextCallBack()
      }
  }

  onBeforeRouteLeave((to, from, next) => {
    nextCallBack = null
    let newValueStr = dataString()
    if (originalValueStr != newValueStr) {
      nextCallBack = next
      confirmationLeaveDialog.value.show()
    } else {
      next()
    }
  })  

  const user = ref(newUser())
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)

  watch(
    () => props.id,
    (newValue) => {
        loadUser(newValue)
      },
    {immediate: true}  
    )

</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really wish to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  

  <user-detail
    :user="user"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></user-detail>
</template>
