<script setup>
  import { ref, inject } from 'vue'
  import { useRouter } from 'vue-router'  
  import { useUserStore } from '../../stores/user.js'
  const router = useRouter()  
  const toast = inject('toast')

  const credentials = ref({
    name: '',
    email: '', 
    password: '',
    type: ''
    })

  const userStore = useUserStore()     

  const emit = defineEmits(['users'])

  const register = async () => {

    if (await userStore.register(credentials.value)) {
      toast.success('Registo Bem sucedido')
      emit('users')
    } else if (credentials.value.password != credentials.value.password1 || credentials.value.password == '' || credentials.value.password1 == '') {
      credentials.value.password = ''
      credentials.value.password1 = ''
      toast.error('password não é compativel')
    }
    else if (credentials.value.name == ''){
      credentials.value.name  = ''
      toast.error('name invalido')
  }else{
      credentials.value.email = ''
      toast.error('Email invalido')
      }
    }
</script>


<template>
    <form
      class="row g-3 needs-validation"
      novalidate
      @submit.prevent="users"
    >
      <h3 class="mt-5 mb-3">Registar</h3>
      <hr>
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
      <div class="mb-3">
          <label
            for="inputEmail"
            class="form-label"
          >Email</label>
          <input
            type="text"
            class="form-control"
            id="inputEmail"
            required
            v-model="credentials.email"
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
      <!--
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
      -->
      <!-- 
     <label>Tipo de Utilizador</label>
      <select name="type">
        <option value="C">C</option>
        <option value="EC">EC</option>
        <option value="ED">ED</option>
        <option value="EM">EM</option>

        <input
            type="text"
            class="form-control"
            id="inputType"
            required
            v-model="credentials.type"
          >

      </select>
  -->
  <div class="mb-3">
          <label
            for="inputType"
            class="form-label"
          >Type</label>
          <input
            type="text"
            class="form-control"
            id="inputType"
            required
            v-model="credentials.type"
          >
      </div>

      <div class="mb-3 d-flex justify-content-center">
        <button
          type="button"
          class="btn btn-primary px-5"
          @click="register"
        >Registar</button>
      </div>


      
    </form>
  </template>

