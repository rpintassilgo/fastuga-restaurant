<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import OrderTable from "./OrderTable.vue"
  import { useOrdersStore } from "../../stores/orders.js"

  const router = useRouter()

  const axios = inject("axios")
  const toast = inject("toast")
  const ordersStore = useOrdersStore()

  const orderToDelete = ref(null)
  const filterByStatus = ref('')
  const deleteConfirmationDialog = ref(null)  
  
  /* Change this function */
  const loadOrders = () => {
    orders.loadProjects()
      .catch((error) => {
        console.log(error)
      })
  }

  const addOrder = () => {
    router.push({ name: 'NewOrder'})
  }
  
  const editOrder = (order) => {
    router.push({ name: 'Order', params: { id: order.id } })
  }

  /* Change this function */
  const deleteOrderConfirmed = () => {
    ordersStore.deleteOrder(orderToDelete.value)
      .then((deletedOrder) => {
        toast.info("Order " + orderToDeleteDescription.value + " was deleted")
      })
      .catch(() => {
        toast.error("It was not possible to delete Order " + orderToDeleteDescription.value + "!")
      })
  }

  const clickToDeleteOrder = (order) => {
    orderToDelete.value = order
    deleteConfirmationDialog.value.show()
  }


  /* Change this function */
  const filteredOrders = computed(()=>{
    return ordersStore.getOrdersByFilter(filterByStatus.value)
  })


  const orderToDeleteDescription = computed(() => {
    return orderToDelete.value
    ? `#${orderToDelete.value.id} (${orderToDelete.value.name})`
    : ""
  })

  onMounted(() => {
    // Calling loadProjects refresh the list of projects from the API
    loadProjects()
  })

</script>

<template>
  <confirmation-dialog
    ref="deleteConfirmationDialog"
    confirmationBtn="Delete order"
    :msg="`Do you really want to delete order ${orderToDeleteDescription}?`"
    @confirmed="deleteOrderConfirmed"
  >
  </confirmation-dialog>

  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Orders</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalProjects }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectStatus"
        class="form-label"
      >Filter by status:</label>
      <select
        class="form-select"
        id="selectStatus"
        v-model="filterByStatus"
      >
        <option :value="null"></option>
        <option value="P">Pending</option>
        <option value="W">Work In Progress</option>
        <option value="C">Completed</option>
        <option value="I">Interrupted</option>
        <option value="D">Discarded</option>
      </select>
    </div>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectOwner"
        class="form-label"
      >Filter by owner:</label>
      <select
        class="form-select"
        id="selectOwner"
        v-model="filterByResponsibleId"
      >
        <option :value="null"></option>
        <option
          v-for="user in users"
          :key="user.id"
          :value="user.id"
        >{{user.name}}</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-success px-4 btn-addprj"
        @click="addProject"
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Project</button>
    </div>
  </div>
  <project-table
    :projects="filteredProjects"
    :showId="true"
    :showDates="true"
    @edit="editProject"
    @delete="clickToDeleteProject"
  ></project-table>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addprj {
  margin-top: 1.85rem;
}
</style>
