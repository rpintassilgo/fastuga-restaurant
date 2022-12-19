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


  const Search = () => {
    console.log (searchQuery.value);
    console.log (page.value);
    const getUsersUrl = searchQuery.value == "all" ? `users?page=${page.value}` : (`users/${searchQuery.value}?page=${page.value}`)
    console.log (getUsersUrl);        

    axios.get(getUsersUrl)
        .then((response) => {
          users.value = response.data.data
          paginationData.value = response.data.meta
        })
        .catch((error) => {
          users.value = []
          console.log(error)
        })
  }
  

  const loadUsers = () => {

    const getUsersUrl = filterByType.value == "all" ? `users?page=${page.value}` : 
                        (filterByType.value == "customer" ? `customers?page=${page.value}` : `users/${filterByType.value}?page=${page.value}`)
                      
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
    Search()
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

  onMounted (() => {
    loadUsers()
    Search()
  })

  
</script>

<template>
  <h3 class="mt-5 mb-3">Users</h3>
    <div class="row">
      <div class="search-wrapper panel-heading col-sm-12">
        <div class="input-group">
          <input class="form-control" type="text" v-model="searchQuery" placeholder="Search user by email" />
          <button type="button" class="btn btn-primary px-5" @click="Search">Search</button>
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

