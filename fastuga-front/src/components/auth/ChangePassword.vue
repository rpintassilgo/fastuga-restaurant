<script setup>
   import { ref, inject } from 'vue'
  import { useUserStore } from '../../stores/user.js'
  const axios = inject('axios')
  const userStore = useUserStore()   
  const toast = inject('toast')  


  const passwords = ref({
        //current_password: '',
        password: '',
        //password_confirm: ''
    })

  const emit = defineEmits(['changedPassword'])

  const dataString = () => {
      return JSON.stringify(user.value)
  }

  const changePassword = () => {
    errors.value = null
      axios.put('users/' + userStore.userId, passwords.value)
        .then((response) => {
          passwords.value = response.data.data
          originalValueStr = dataString()
          toast.success('User #' + passwords.value.id + ' was updated successfully.')
          router.back()
        })
        .catch((error) => {
          if (error.response.status == 422) {
              toast.error('User #' + userStore.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('User #' + userStore.id + ' was not updated due to unknown server error!')
            }
        })
      emit('changedPassword')
  }

   const errors = ref(null)
</script>

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="changePassword"
  >
    <h3 class="mt-5 mb-3">Mudar a Password</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputCurrentPassword"
          class="form-label"
        >Current Password</label>
        <input
          type="password"
          class="form-control"
          id="inputCurrentPassword"
          required
          v-model="passwords.current_password"
        >
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputPassword"
          class="form-label"
        >New Password</label>
        <input
          type="password"
          class="form-control"
          id="inputPassword"
          required
          v-model="passwords.password"
        >
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputPasswordConfirm"
          class="form-label"
        >Password Confirmmation</label>
        <input
          type="password"
          class="form-control"
          id="inputPasswordConfirm"
          required
          v-model="passwords.password_confirm"
        >
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="changePassword"
      >Mudar a Password</button>
    </div>
  </form>
</template>

