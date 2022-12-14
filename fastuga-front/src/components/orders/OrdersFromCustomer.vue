<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import OrderTable from "./OrderTable.vue"
  import { useOrdersStore } from "../../stores/orders.js"

  const router = useRouter()

  const axios = inject("axios")
  const toast = inject("toast")
  const ordersStore = useOrdersStore()

  const ordersFromCustomer = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const orderToCancel = ref(null)
  const filterByStatus = ref('all')
  const deleteConfirmationDialog = ref(null) 
  const customer = ref(null)

  const props = defineProps({
      id: { //user id from customer
        type: Number,
        default: null
      }
  })

  const user_id = ref(props.id)
  
  /* Change this function */
  const loadOrders = () => {
    const getUsersUrl = filterByStatus.value == "all" ? `orders/customer/${user_id.value}?page=${page.value}` : 
                                                `orders/customer/${user_id.value}/${filterByStatus.value}?page=${page.value}`
    axios.get(getUsersUrl)
        .then((response) => {
          console.log(response)
         // users.value.splice(0)
          orders.value = response.data.data
          paginationData.value = response.data.meta
        })
        .catch((error) => {
          orders.value = []
          console.log(error)
        })
  }

  // get customer from user_id
  const loadCustomer = () => {
    console.log(user_id.value)
      axios.get(`customers/${user_id.value}`)
          .then((response) => {
            console.log(response)
            customer.value = response.data
          })
          .catch((error) => {
            customer.value = null
            console.log(error)
          })
  }

  const resetPage = () => {
    page.value = 1
    loadOrders()
  }

  const addOrder = () => {
    router.push({ name: 'NewOrder'})
  }


  /* Change this function */
  const cancelOrderConfirmed = () => {
    ordersStore.changeStatusOrder(orderToCancel.value,"cancel")
      .then((cancelledOrder) => {
        toast.info("Order " + orderToCancelDescription.value + " was cancelled")
      })
      .catch(() => {
        toast.error("It was not possible to cancel Order " + orderToCancelDescription.value + "!")
      })
  }

  const clickToCancelOrder = (order) => {
    orderToCancel.value = order
    cancelConfirmationDialog.value.show()
  }


  /* Change this function */
  const filteredOrders = computed(()=>{
    return ordersStore.getOrdersByFilter(filterByStatus.value)
  })


  const orderToCancelDescription = computed(() => {
    return orderToCancel.value
    ? `#${orderToCancel.value.id} (${orderToCancel.value.date})`
    : ""
  })

  onMounted(() => {
   // loadCustomer()
    // Calling loadProjects refresh the list of projects from the API
    loadOrders()
  })

</script>

<template>
  <confirmation-dialog
    ref="cancelConfirmationDialog"
    confirmationBtn="Cancel order"
    :msg="`Do you really want to cancel order ${orderToCancelDescription}?`"
    @confirmed="cancelOrderConfirmed"
  >
  </confirmation-dialog>
        <h3 class="mt-5 mb-3">Orders from User #{{customer.user_id}} ({{customer.name}})</h3>
      <div class="mx-2 mt-2 flex-grow-1">
      <label
        for="selectType"
        class="form-label"
      >Filter by order status:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByStatus"
        @change="resetPage"
      >
        <option value="all">All</option>
        <option value="preparing">Preparing</option>
        <option value="ready">Ready</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>
  <hr>

  <order-table
    :orders="ordersFromCustomer"
    :showId="true"
    :showDates="true"
    @edit="editOrder"
    @delete="clickToDeleteOrder"
  ></order-table>
  <template class="paginator">
    <pagination
      v-model="page"
      :records="paginationData ? paginationData.total : 0"
      :per-page="paginationData ? paginationData.per_page : 0"
      @paginate="loadOrders"
      :options="{hideCount: true}">
    </pagination>
  </template>
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

.paginator {
  display: flex;
  justify-content: center;
}
</style>
