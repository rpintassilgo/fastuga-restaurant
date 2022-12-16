<script setup>
  import { ref, computed, onBeforeMount, onMounted, inject } from 'vue'
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
  const firstLoad = ref(1)

  const props = defineProps({
      id: { //user id from customer
        type: Number,
        default: null
      }
  })

  //const user_id = ref(props.id)
  
  /* Change this function */
  const loadOrders = () => {
    const getUsersUrl = filterByStatus.value == "all" ? `orders/customer/${props.id}?page=${page.value}` : 
                                                `orders/customer/${props.id}/${filterByStatus.value}?page=${page.value}`
    axios.get(getUsersUrl)
        .then((response) => {
          //console.log("loadOrders: " + response)
         // users.value.splice(0)
          ordersFromCustomer.value = response.data.data
          paginationData.value = response.data.meta
          firstLoad.value = firstLoad.value + 1
        })
        .catch((error) => {
          orders.value = []
          console.log(error)
        })
  }

  // get customer from id
  const loadCustomer = () => {
    console.log("props.id (loadCustomer): " + props.id)
      axios.get(`customers/${props.id}`)
          .then((response) => {
            console.log("then get customer: " + JSON.stringify(response.data.data))
            customer.value = response.data.data
          })
          .catch((error) => {
            customer.value = null
            console.log("loadCustomer error: " + error.message)
          })
  }

  const resetPage = () => {
    page.value = 1
    //loadCustomer()
    loadOrders()
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



  const orderToCancelDescription = computed(() => {
    return orderToCancel.value
    ? `#${orderToCancel.value.id} (${orderToCancel.value.date})`
    : ""
  })

  onBeforeMount(() => {
    loadCustomer()
  })

  onMounted(() => {
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
        <h3 class="mt-5 mb-3">Orders from {{customer?.name}}</h3>

        <h1 v-if="(ordersFromCustomer.length === 0)  && (firstLoad === 1)" class="mt-5 mb-3">No orders</h1>
        <div v-if="!((ordersFromCustomer.length === 0) && (firstLoad === 1))">
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
              :showCustomerId="false"
              :showCancelButton="false"
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

          </div>
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
