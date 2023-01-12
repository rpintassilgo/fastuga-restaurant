<script setup>
  import { ref, inject } from 'vue'
  import { useUserStore } from "../../stores/user.js"
  
  const userStore = useUserStore()
  const toast = inject("toast")

  const passwords = ref({
        current_password: '',
        password: '',
        password_confirm: ''
    })

  const emit = defineEmits(['changedPassword'])

  const changePassword = () => {
      try {
        if(passwords.value.password != passwords.value.password_confirm) throw new Error("Passwords don't match!");
        if(passwords.value.password == "") throw new Error("Insert new password!");
        userStore.changePassword(userStore.user.id,{
          'current_password': passwords.value.current_password,
          'new_password': passwords.value.password
        })
        toast.success("Password change success!")
      } catch (error) {
        console.log(error.message)
        toast.error("Password change failed!")
      }
  }
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
        >Repeat new password</label>
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

