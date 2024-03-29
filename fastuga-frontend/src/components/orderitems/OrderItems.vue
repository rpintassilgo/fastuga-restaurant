<script setup>
  import { ref, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import { useOrderItemsStore } from "../../stores/orderItems.js"
  import { useOrdersStore } from "../../stores/orders.js"
  import OrderItemTable from "./OrderItemTable.vue"

  const router = useRouter()

  const axios = inject("axios")
  const toast = inject("toast")

  const ordersStore = useOrdersStore()
  const orderItemsStore = useOrderItemsStore()
  const orderItems = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const filterByStatus = ref('waiting')
  

 const loadHotDishes= () => {
    const getUsersUrl = filterByStatus.value == "all" ? `orderitems/hotdishes?page=${page.value}` : 
                                                `orderitems/hotdishes/${filterByStatus.value}?page=${page.value}`

    axios.get(getUsersUrl)
        .then((response) => {
          orderItems.value = response.data.data
          paginationData.value = response.data.meta
        })
        .catch((error) => {
          orderItems.value = []
          console.log(error)
        })
  }

  const resetPage = () => {
    page.value = 1
    loadHotDishes()
  }

  const prepareOrderItem = (orderItem) => {
    try {
        orderItemsStore.changeStatusOrderItem(orderItem.id,"preparing")
        loadHotDishes()
        toast.success("Order item #" + orderItem.id + " is being prepared!")
    } catch (error) {
        toast.error("Order item #" + orderItem.id + " didn't start the preparation!")
    }
  }

   const readyOrderItem = async (orderItem) => {
    try {
        orderItemsStore.changeStatusOrderItem(orderItem.id,"ready")
        await checkIfOrderReady(orderItem)
        loadHotDishes()
        toast.success("Order item #" + orderItem.id + " is ready!")    
    } catch (error) {
        toast.error("It was not possible to set order item #" + orderItem.id + " as ready!")
    }
  }

  const checkIfOrderReady = async (orderItem) => {
    try {
      const response = await axios.get(`orderitems/${orderItem.id}`)
      const response2 = await axios.get(`orderitems/hotdishes/order/${response.data.data.order_id}`)
      let isReady = true
      response2.data.data.forEach((order_item) => {
        if(order_item.status != 'R') isReady = false
      });
      if(isReady) ordersStore.changeStatusOrder(response.data.data.order_id,'ready')
      
    } catch (error) {
      console.log(error.message)
    }
  }


  onMounted(() => {
    loadHotDishes()
  })

</script>

<template>
      <h3 class="mt-5 mb-3">Order Items</h3>
      <div class="mx-2 mt-2 flex-grow-1">
      <label
        for="selectType"
        class="form-label"
      >Filter by order item status:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByStatus"
        @change="resetPage"
      >
        <option value="all">All</option>
        <option value="waiting">Waiting</option>
        <option value="preparing">Preparing</option>
        <option value="ready">Ready</option>
      </select>
    </div>
  <hr>

  <order-item-table
    :orderItems="orderItems"
    :showOrderId="true"
    @ready="readyOrderItem"
    @prepare="prepareOrderItem"
  ></order-item-table>
  <template class="paginator">
    <pagination
      v-model="page"
      :records="paginationData ? paginationData.total : 0"
      :per-page="paginationData ? paginationData.per_page : 0"
      @paginate="loadHotDishes"
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
