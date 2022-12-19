<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import UserTable from "./UserTable.vue"
  
  const router = useRouter()

  const axios = inject('axios')

  const users = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const filterByType = ref("all")
  const searchQuery = ref("")

  const totalUsers = computed(() => {
    return paginationData ? paginationData.total : 0
  })



  const loadUsers = () => {

    const getUsersUrl = searchQuery.value == "" ? (filterByType.value == "all" ? `users?page=${page.value}` : 
    (filterByType.value == "customer" ? `customers?page=${page.value}` : `users/type/${filterByType.value}?page=${page.value}`))
  
    : (filterByType.value == "all" ? `users/email/${searchQuery.value}?page=${page.value}` : (filterByType.value == "customer" ? 
     `customers/email/${searchQuery.value}?page=${page.value}` : `users/type/${filterByType.value}/${searchQuery.value}?page=${page.value}`))
                        
     console.log(getUsersUrl)
    axios.get(getUsersUrl)
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

  const resetPage = () => {
    page.value = 1
    loadUsers()
  }

  // add admin or employee
  const addUser = () => {
    router.push({ name: 'NewUser'})
  }

  const editUser = (user) => {
    router.push({ name: 'User', params: { id: user.id } })
  }

  const ordersCustomer = (user) => {
    router.push({ name: 'OrdersFromCustomer', params: { id: user.id } })
  }

  const clearSearch = () => {
    searchQuery.value = '';
    loadUsers();
  }
  onMounted (() => {
    loadUsers()
  })

  
</script>

<template>
  <h3 class="mt-5 mb-3">Users</h3>
    <div class="row">
      <div class="search-wrapper panel-heading col-sm-12">
        <div class="input-group">
          <input class="form-control" type="text" v-model="searchQuery" placeholder="Search user by email" />
          <button type="button" class="btn btn-primary px-5" @click="loadUsers">Search</button>
          
          <button type="button" class="btn btn-primary px-5" @click="clearSearch">Clear</button>
        </div>
      </div>                        
    </div>
    <div class="mx-2 mt-2 flex-grow-1 ">
      <label
        for="selectType"
        class="form-label"
      >Filter by user type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByType"
        @change="resetPage"
      >
        <option value="all">All</option>
        <option value="customer">C (Customer)</option>
        <option value="chef">EC (Employee Chef)</option>
        <option value="delivery">ED (Employee Delivery)</option>
        <option value="manager">EM (Employee Manager)</option>
      </select>
    </div>

  <hr>
  <user-table
    :users="users"
    :showId="false"
    @edit="editUser"
    @orders="ordersCustomer"
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

