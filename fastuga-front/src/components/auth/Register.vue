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
        confirmPassword: '',
        phone: '',
        nif: '',
        default_payment_type: '',
        default_payment_reference: '',
        photo_url: null
    })

  const userStore = useUserStore()     

  const emit = defineEmits(['users'])

  const register = async () => {

    console.log(JSON.stringify(credentials.value))

    if (await userStore.signUpCustomer(credentials.value)) {
      toast.success('Account created')
      emit('register')
      //router.back()
      router.push('/login')
    } else if (credentials.value.password != credentials.value.confirmPassword || credentials.value.password == '' || credentials.value.confirmPassword == '') {
      credentials.value.password = ''
      credentials.value.confirmPassword = ''
      toast.error('Password do not match')
    }
    else if (credentials.value.name == ''){
      credentials.value.name  = ''
      toast.error('Invalid name')
  }else{
      credentials.value.email = ''
      toast.error('Invalid email')
      }
    }
</script>


<template>
    <form
      class="row g-3 needs-validation"
      novalidate
      @submit.prevent="users"
    >
      <h3 class="mt-5 mb-3">Sign up</h3>
      <hr>
      <div class="mb-3">
        <div class="mb-3">
          <label
            for="inputName"
            class="form-label"
          >Name</label>
          <input
            type="text"
            class="form-control"
            id="inputName"
            required
            v-model="credentials.name"
      >
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
      
      <div class="mb-3">
          <label
            for="inputConfirmPassword"
            class="form-label"
          >Confirm Password</label>
          <input
            type="password"
            class="form-control"
            id="inputConfirmPassword"
            required
            v-model="credentials.confirmPassword"
          >
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label
            for="inputPhone"
            class="form-label"
          >Phone number</label>
          <input
            type="text"
            class="form-control"
            id="inputPhone"
            required
            v-model="credentials.phone"
          >
        </div>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label
            for="inputNif"
            class="form-label"
          >NIF</label>
          <input
            type="text"
            class="form-control"
            id="inputNif"
            required
            v-model="credentials.nif"
          >
        </div>
      </div>
      <div class="mb-3">
            <label
              for="selectType"
              class="form-label"
            >Default Payment Type:</label>
            <select
              class="form-select"
              id="selectDefaultPaymentType"
              v-model="credentials.default_payment_type"
            >
              <option value="VISA">Visa</option>
              <option value="PAYPAL">Paypal</option>
              <option value="MBWAY">MBWay</option>
            </select>
      </div>
      <div class="mb-3">
          <label
            for="inputDefaultPaymentReference"
            class="form-label"
          >Default Payment Reference</label>
          <input
            type="text"
            class="form-control"
            id="inputDefaultPaymentReference"
            required
            v-model="credentials.default_payment_reference"
          >
       </div>

      <!-- meter o upload da foto aqui depois, ou senao se a pessoa quiser q vá ao perfil e troque-->
  
      <div class="mb-3 d-flex justify-content-center">
        <button
          type="button"
          class="btn btn-primary px-5"
          @click="register"
        >Registar</button>
      </div>


      
    </form>
  </template>

