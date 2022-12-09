<script setup>
  import { ref, inject } from 'vue'
  import { useRouter } from 'vue-router'  
  import { useUserStore } from '../../stores/user.js'
  const router = useRouter()  
  const toast = inject('toast')

  const credentials = ref({
        username: '',
        name: '',
        password: '',
        password1: '',
        gender: '',
    })

  const userStore = useUserStore()     

  const emit = defineEmits(['register'])

  const register = async () => {

    if (await userStore.register(credentials.value)) {
      toast.success('Registo Bem sucedido')
      emit('register')
      //router.back()
      router.push('/login')
    } else if (credentials.value.password != credentials.value.password1 || credentials.value.password == '' || credentials.value.password1 == '') {
      credentials.value.password = ''
      credentials.value.password1 = ''
      toast.error('password do not match')
    }
    else if (credentials.value.name == ''){
      credentials.value.name  = ''
      toast.error('name not valid')
  }else{
      credentials.value.username = ''
      toast.error('username not valid')
      }
    }
</script>


<template>
    <form
      class="row g-3 needs-validation"
      novalidate
      @submit.prevent="register"
    >
      <h3 class="mt-5 mb-3">Registar</h3>
      <hr>
      <div class="mb-3">
          <label
            for="inputUsername"
            class="form-label"
          >Email</label>
          <input
            type="text"
            class="form-control"
            id="inputUsername"
            required
            v-model="credentials.username"
          >
      </div>
      <div class="mb-3">
          <label
            for="inputPassword"
            class="form-label"
          >Password</label>
          <input
            type="password"
            class="form-control"
            id="inputPassword"
            required
            v-model="credentials.password"
          >
      </div>
      <div class="mb-3">
          <label
            for="inputPassword1"
            class="form-label"
          >Confirmação da Password</label>
          <input
            type="password"
            class="form-control"
            id="inputPassword1"
            required
            v-model="credentials.password1"
          >
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label
            for="inputName"
            class="form-label"
          >Nome</label>
          <input
            type="text"
            class="form-control"
            id="inputName"
            required
            v-model="credentials.name"
          >
        </div>
      </div>

      <select name="gender">
        <option value="M">M</option>
        <option value="F">F</option>

        <input
            type="text"
            class="form-control"
            id="inputGender"
            required
            v-model="credentials.gender"
          >

      </select>
  
      <div class="mb-3 d-flex justify-content-center">
        <button
          type="button"
          class="btn btn-primary px-5"
          @click="register"
        >Registar</button>
      </div>


      
    </form>
  </template>

