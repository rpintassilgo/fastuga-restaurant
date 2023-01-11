<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import UserTable from "./UserTable.vue"
  
  const router = useRouter()

  const axios = inject('axios')
  const toast = inject("toast")

  const users = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const filterByType = ref("all")
  const search = ref("")

  const totalUsers = computed(() => {
    return paginationData ? paginationData.total : 0
  })

  const loadUsers = () => {

    const getUsersUrl = search.value == "" ? (filterByType.value == "all" ? `users?page=${page.value}` :
    (filterByType.value == "customer" ? `customers?page=${page.value}` : `users/type/${filterByType.value}?page=${page.value}`))

    : (filterByType.value == "all" ? `users/email/${search.value}?page=${page.value}` : (filterByType.value == "customer" ?
     `customers/email/${search.value}?page=${page.value}` : `users/type/${filterByType.value}/${search.value}?page=${page.value}`))

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

  const clearSearch = () => {
    search.value = '';
    loadUsers();
  }

  const resetPage = () => {
    page.value = 1
    loadUsers()
  }

 const deletedUser = (deletedUser) => {
    let idx = users.value.findIndex((p) => p.id === deletedUser.data.id)
    if (idx >= 0) users.value.splice(idx,1)
 }

  const editUser = (user) => {
    router.push({ name: 'User', params: { id: user.id } })
  }

  const ordersCustomer = (user) => {
    router.push({ name: 'OrdersFromCustomer', params: { id: user.id } })
  }



const blockUser = (user) => {
  if(user.type != "C"){
    axios
    .put("users/block",user)
    .then(() => {
      loadUsers()
      toast.error("User  #" + user.id + " was blocked")
    })
    .catch((error) => {
      console.log(error)
    })
  } else{
    axios
    .put("customers/block",user)
    .then(() => {
      loadUsers()
      toast.error("User  #" + user.id + " was blocked")
    })
    .catch((error) => {
      console.log(error)
    })
  }
}

const unblockUser = (user) => {
  if(user.type != "C"){
    axios
    .put("users/unblock",user)
    .then(() => {
      loadUsers()
      toast.success("User  #" + user.id + " was unblocked")
    })
    .catch((error) => {
      console.log(error)
    })
  } else{
    axios
    .put("customers/unblock",user)
    .then(() => {
      loadUsers()
      toast.success("User  #" + user.id + " was unblocked")
    })
    .catch((error) => {
      console.log(error)
    })
  }
}

const changeBlock = (user) => {
  user.blocked == 1 ? unblockUser(user) : blockUser(user)
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
          <input class="form-control" type="text" v-model="search" placeholder="Search user by email" />

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
    @deleted="deletedUser"
    @block="changeBlock"
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

