<script setup>
import { ref, computed, watch, inject } from "vue"
import avatarNoneUrl from '@/assets/avatar-none.png'
import { useUserStore } from "../../stores/user.js"

const serverBaseUrl = inject("serverBaseUrl")
const userStore = useUserStore()

const props = defineProps({
  users: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
  },
  showEmail: {
    type: Boolean,
    default: true,
  },
  showType: {
    type: Boolean,
    default: true,
  },
  showBlocked: {
    type: Boolean,
    default: true,
  },
  showPhoto: {
    type: Boolean,
    default: true,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
  showBlockButton: {
    type: Boolean,
    default: true,
  },
  showDeleteButton: {
    type: Boolean,
    default: true,
  },
  showOrdersButton: {
    type: Boolean,
    default: true,
  }
})

const emit = defineEmits(["edit","deleted","orders"])

const editingUsers = ref(props.users)
const userToDelete = ref(null)
const deleteConfirmationDialog = ref(null)

const userToDeleteDescription = computed(() => {
  return userToDelete.value
    ? `#${userToDelete.value.id} `
    : ""
})

watch(
  () => props.users,
  (newUsers) => {
    editingUsers.value = newUsers
  }
)

const photoFullUrl = (user) => {
  return user.photo_url
    ? serverBaseUrl + "/storage/fotos/" + user.photo_url
    : avatarNoneUrl
}

const editClick = (user) => {
  emit("edit", user)
}

const ordersClick = (customer) => {
  emit("orders", customer)
}

const canViewUserDetail  = (userId) => {
  if (!userStore.user) {
    return false
  }
  return userStore.user.type == 'EM' || userStore.user.id == userId
}

const dialogConfirmedDelete = () => {
  axios
    .delete("users/" + userToDelete.value.id)
    .then((response) => {
      emit("deleted", response.data)
      toast.info("User " + userToDeleteDescription.value + " was deleted")
    })
    .catch((error) => {
      console.log(error)
    })
}

const deleteClick = (user) => {
  userToDelete.value = user
  deleteConfirmationDialog.value.show()
}
</script>

<template>
  <!-- meter aqui um filtro por tipo de utilizador -->
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId" class="align-middle">#</th>
        <th v-if="showPhoto" class="align-middle">Photo</th>
        <th class="align-middle">Name</th>
        <th v-if="showEmail" class="align-middle">Email</th>
        <th v-if="showBlocked" class="align-middle">Blocked?</th>
        <th v-if="showType" class="align-middle">Type</th>
        <th v-if="showEditButton || showDeleteButton"></th>
        <th v-if="showOrdersButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td v-if="showId" class="align-middle">{{ user.id }}</td>
        <td v-if="showPhoto" class="align-middle">
          <img :src="photoFullUrl(user)" class="rounded-circle img_photo" />
        </td>
        <td class="align-middle">{{ user.name }}</td>
        <td v-if="showEmail" class="align-middle">{{ user.email }}</td>
        <td v-if="showBlocked" class="align-middle">{{ user.blocked == 1 ? "Yes" : "No" }}</td>
        <td v-if="showType" class="align-middle">{{ user.type }}</td>
        
        <!-- edit / block / delete buttons -->
        <td class="text-end align-middle" v-if="showEditButton || showBlockButton || showDeleteButton">
          <div class="d-flex justify-content-end" v-if="canViewUserDetail(user.id)">

            <button 
              class="btn btn-xs btn-light" 
              @click="editClick(user)" v-if="showEditButton">
              <i class="bi bi-xs bi-pencil"></i>
            </button>

            <button 
              class="btn btn-xs btn-light" 
               v-if="showBlockButton"> <!-- tratar do botao para bloquear utilizadores -->
              <i class="bi bi-xs bi-lock"></i>
            </button>

            <button
              class="btn btn-xs btn-light"
              @click="deleteClick(user)" v-if="showDeleteButton">
              <i class="bi bi-xs bi-trash3"></i>
            </button>

          </div>
        </td>

        <!-- orders buttons -->
        <td class="text-end" v-if="showOrdersButton">
          <div class="d-flex justify-content-end">
            <button
              v-if="user.type =='C'"
              @click="ordersClick(user)"
              class="btn btn-xs btn-success"
            >Orders
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>
