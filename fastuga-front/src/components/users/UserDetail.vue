<script setup>
import { ref, watch, computed, inject, toDisplayString } from "vue";
import avatarNoneUrl from '@/assets/avatar-none.png'

const serverBaseUrl = inject("serverBaseUrl");
const axiosImage = inject('axiosImage')
const toast = inject('toast')

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false
  },
})

const emit = defineEmits(["save", "cancel","photo"]);


const editingUser = ref(props.user)
const photoUrl = ref("");


watch(
  () => props.user,
  (newUser) => {
    editingUser.value = newUser
  },
  /*
  { immediate: true 
  }
  */
)

const photoFullUrl = computed(() => {
  return photoUrl.value == "" ? (editingUser.value.photo_url
    ? serverBaseUrl + "/storage/fotos/" + editingUser.value.photo_url
    : avatarNoneUrl) : photoUrl.value
})

const imageChange = (event) => {
  editingUser.value.photo_file = event.target.files[0];
  //photoUrl = URL.createObjectURL(editingProduct.value.photo_file);
}

const save = () => {
  emit("save", editingUser.value);
}

const cancel = () => {
  emit("cancel", editingUser.value);
}

</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">User #{{ editingUser.id }}</h3>
    <hr />
    <div class="d-flex flex-wrap justify-content-between">
      <div class="w-75 pe-4">
        <div class="mb-3">
          <label for="inputName" class="form-label">Name</label>
          <input
            type="text"
            class="form-control"
            id="inputName"
            placeholder="Name"
            required
            v-model="editingUser.name"
          />
          <field-error-message
            :errors="errors"
            fieldName="nome"
          ></field-error-message>
        </div>

        <div class="mb-3 px-1">
          <label for="inputEmail" class="form-label">Email</label>
          <input
            type="email"
            class="form-control"
            id="inputEmail"
            placeholder="Email"
            required
            v-model="editingUser.email"
          />
          <field-error-message
            :errors="errors"
            fieldName="email"
          ></field-error-message>
        </div>

        <div class="mb-3 px-1">
          <label for="inputPassword" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="inputPassword"
            placeholder="Password"
            required
            v-model="editingUser.password"
          />
          <field-error-message :errors="errors" fieldName="password"></field-error-message>
        </div>

        <div class="mb-3">
          <label for="selectType" class="form-label">Type:</label>
          <select
            class="form-select"
            id="selectType"
            v-model="editingUser.type"
          >
            <option value="EC">Employee Chef</option>
            <option value="ED">Employee Delivery</option>
            <option value="EM">Employee Manager</option>
          </select>
        </div>
      </div>
      <div class="w-25">
        <div class="mb-3">
          <label class="form-label">Photo</label>
          <div class="form-control text-center">
            <img :src="photoFullUrl" class="w-100" />
          </div>
          <div class="form-control text-center">
          <input type="file" accept="image/*" class="form-control-file" id="actual-btn" v-on:change="imageChange"/>
          
        
          
        </div>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">
        Save
      </button>
      <button type="button" class="btn btn-light px-5" @click="cancel">
        Cancel
      </button>
    </div>
    </div>
  </form>
</template>

<style scoped>
.btn-new-one {
  background-color: #0d6efd;
  color: white;
  padding: 0.5rem;
  font-family: sans-serif;
  border-radius: 0.3rem;
  cursor: pointer;
  margin-top: 1rem;
}
</style>
