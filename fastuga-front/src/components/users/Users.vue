<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import UserTable from "./UserTable.vue"
  
  const router = useRouter()

  const axios = inject('axios')

  const users = ref([])
  const paginationData = ref(null)
  const page = ref(1)

  const totalUsers = computed(() => {
    return paginationData ? paginationData.total : 0
  })

  const loadUsers = () => {
    axios.get('users?page=' + page.value)
        .then((response) => {
          console.log(response)
         // users.value.splice(0)
          users.value = response.data.data
          paginationData.value = response.data.meta
        })
        .catch((error) => {
          users.value = []
          console.log(error)
        })
    }

  const editUser = (user) => {
    router.push({ name: 'User', params: { id: user.id } })
  }

  onMounted (() => {
    loadUsers()
  })
</script>

<template>
  <h3 class="mt-5 mb-3">Users</h3>
  <hr>
  <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
  ></user-table>
  <template class="paginator">
    <pagination
      v-model="page"
      :records="paginationData ? paginationData.total : 0"
      :per-page="paginationData ? paginationData.per_page : 0"
      @paginate="loadUsers"
      :options="{hideCount: true}">
    </pagination>
  </template>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}

.paginator {
  display: flex;
  justify-content: center;
}
</style>

