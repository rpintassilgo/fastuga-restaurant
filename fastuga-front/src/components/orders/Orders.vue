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
  const orderToDelete = ref(null)
  const filterByStatus = ref('')
  const deleteConfirmationDialog = ref(null)  
  
  /* Change this function */
  const loadOrders = () => {
    axios.get('orders')
      .then((response) => {
        orders.value = response.data
      })
      .catch((error) => {
        console.log(error)
      })
  }

  const addOrder = () => {
    router.push({ name: 'NewOrder'})
  }
  
  /* nao sei se vai ser possivel editar um pedido
  const editOrder = (order) => {
    router.push({ name: 'Order', params: { id: order.id } })
  }
  */

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

  <order-table
    :orders="orders"
    :showId="true"
    :showDates="true"
    @edit="editProject"
    @delete="clickToDeleteProject"
  ></order-table>
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
