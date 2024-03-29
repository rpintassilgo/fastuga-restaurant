<script setup>
  import { ref, onMounted } from 'vue'
  import { useUserStore } from "../../stores/user.js"

  const userStore = useUserStore()

  const props = defineProps({
    orders: {
      type: Array,
      default: () => [],
    },
    showId: {
      type: Boolean,
      default: false,
    },
    showTicketNumber: {
      type: Boolean,
      default: true,
    },
    showStatus: {
      type: Boolean,
      default: true,
    },
    showCustomerId: {
      type: Boolean,
      default: true,
    },
    showTotalPrice: {
      type: Boolean,
      default: true,
    },
    showTotalPaid: {
      type: Boolean,
      default: false,
    },
    showTotalPaidWithPoints: {
      type: Boolean,
      default: false,
    },
    showPointsGained: {
      type: Boolean,
      default: false,
    },
    showPointsUsedToPay: {
      type: Boolean,
      default: false,
    },
    showPaymentType: {
      type: Boolean,
      default: true,
    },
    showPaymentReference: {
      type: Boolean,
      default: true,
    },
    showDate: {
      type: Boolean,
      default: true,
    },
    showDeliveryBy: {
      type: Boolean,
      default: false,
    },
    showOrderItems: {
      type: Boolean,
      default: false,
    },
    showCancelButton: {
      type: Boolean,
      default: true,
    },
    showDeliverButton: {
      type: Boolean,
      default: false,
    },
    showOrderItemsButton: {
      type: Boolean,
      default: false,
    }
  })

  const emit = defineEmits(["cancel","deliver","orderItems"]);

  const cancelClick = (order) => {
    emit("cancel", order)
  }

  const deliverClick = (order) => {
    emit("deliver", order)
  }

  const orderItemsClick = (order) => {
    emit("orderItems", order)
  }

  const loggedUserType = ref(null)
        

  onMounted(() => {
    loggedUserType.value = userStore.user.type
  })

</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="false">#</th>
        <th v-if="false">Ticket Number</th>
        <th v-if="showStatus">Status</th>
        <th v-if="showCustomerId">Customer #</th>
        <th v-if="showTotalPrice">Total Price</th>
        <th v-if="showTotalPaid">Total Paid</th>
        <th v-if="showTotalPaidWithPoints">Total Paid With Points</th>
        <th v-if="showPointsGained">Points Gained</th>
        <th v-if="showPointsUsedToPay">Points Used</th>
        <th v-if="showPaymentType">Payment type</th>
        <th v-if="showPaymentReference">Payment reference</th>
        <th v-if="showDate">Date</th>
        <th v-if="showDeliveryBy">Deliver #</th>
        <th v-if="showOrderItems">Order Items</th>
        <th v-if="showCancelButton || showDeliverButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="order in orders"
        :key="order.id"
      >
        <td v-if="false">{{ order.ticket_number }}</td>
        <td v-if="showStatus">{{ order.status }}</td>
        <td v-if="showCustomerId">{{ order.customer_id == null ? "No Account" : order.customer_id }}</td>
        <td v-if="showTotalPrice">{{ order.total_price }}</td>
        <td v-if="showPaymentType">{{ order.payment_type }}</td>
        <td v-if="showPaymentReference">{{ order.payment_reference }}</td>
        <td v-if="showDate">{{ order.date }}</td>
        <td
          class="text-end"
          v-if="showCancelButton || showDeliverButton"
        >
          <div class="d-flex justify-content-end" v-if="loggedUserType != 'C'">
            <button
              class="btn btn-xs btn-danger"
              @click="cancelClick(order)"
              v-if="showCancelButton && !(order.status == 'C')"
            >Cancel
            </button>
          </div>
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-info"
              @click="deliverClick(order)"
              v-if="showDeliverButton && order.status == 'R'"
            >Deliver
            </button>
          </div>
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-success"
              @click="orderItemsClick(order)"
              v-if="showOrderItemsButton && order.status != 'C'"
            >Order Items
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>
