<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import OrderTable from "./OrderTable.vue"
  import { useOrdersStore } from "../../stores/orders.js"

  const router = useRouter()

  const axios = inject("axios")
  const toast = inject("toast")
  const ordersStore = useOrdersStore()

  const orders = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const orderToDeliver = ref(null)
  const filterByStatus = ref('all')
  const deliverConfirmationDialog = ref(null)  
  
  /* Change this function */
 const loadOrders = () => {
    const getUsersUrl = filterByStatus.value == "all" ? `orders?page=${page.value}` : `orders/${filterByStatus.value}?page=${page.value}`
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

  const resetPage = () => {
    page.value = 1
    loadOrders()
  }


  const deliverOrderConfirmed = () => {
    ordersStore.changeStatusOrder(orderToDeliver.value.id,"deliver")
      .then(() => {
        toast.info("Order " + orderToDeliverDescription.value + " was delivered")
      })
      .catch((/*e*/) => {
        toast.error("It was not possible to deliver Order " + orderToDeliverDescription.value + "!")
       // console.log("erro: " + e.message)
      })
  }

  const deliverOrder = (order) => {
    orderToDeliver.value = order
    deliverOrderConfirmed()
  }

  const showOrderItems = (order) => {
    router.push({ name: 'OrderItemsVisualization' , params: { order_id: order.id }})
  }


  const orderToDeliverDescription = computed(() => {
    return orderToDeliver.value
    ? `#${orderToDeliver.value.id} (${orderToDeliver.value.date})`
    : ""
  })


  onMounted(() => {
    // Calling loadProjects refresh the list of projects from the API
    loadOrders()
  })

</script>

<template>
  <confirmation-dialog
    ref="deliverConfirmationDialog"
    confirmationBtn="Deliver order"
    :msg="`Do you really want to deliver order ${orderToDeliverDescription}?`"
    @confirmed="deliverOrderConfirmed"
  >
  </confirmation-dialog>
        <h3 class="mt-5 mb-3">Orders</h3>
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
    :orders="orders"
    :showId="true"
    :showDates="true"
    :showDeliverButton="true"
    :showCancelButton="false"
    :showOrderItemsButton="true"
    @deliver="deliverOrder"
    @orderItems="showOrderItems"
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
