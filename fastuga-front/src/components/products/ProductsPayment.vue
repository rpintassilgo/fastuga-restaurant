<script setup>
  import { ref, computed, onMounted, inject, toDisplayString } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  import { useUserStore } from "../../stores/user.js"
  
  const toast = inject('toast')
  const axios = inject('axios')
  const router = useRouter()

  const cart = ref([])
  const total = ref(0)
  const paymentMethod = ref('default')
  const paymentData = ref({
    "type": "",
    "reference": "",
    "value": ""
  })
  const paymentReference = ref('')
  const order = ref({
    'customer_id': null,
    'total_paid': null,
    'total_paid_with_points': 0,
    'points_gained': 0,
    'points_used_to_pay': 0,
    'payment_type': '',
    'payment_reference': '',
    'order_items': []
  })
  const userStore = useUserStore()

  const loadCart = () => {
      cart.value = userStore.loadCartFromLocalStorage()
  }

   const goBack = () => {
      router.back()
  }

  const pay = async () => {
    let status = ""
    if(userStore.user.default_payment_type == "" && userStore.user.default_payment_reference == "" && paymentMethod.value == "default"){
      toast.error("No default payment reference found")
    } else{
          //console.log("dados: " + userStore.user.default_payment_reference + "  |   " + userStore.user.default_payment_type)
          try {
            
            switch (paymentMethod.value) {
                case 'default':
                  paymentData.value.type = userStore.user.default_payment_type.toLowerCase()
                  paymentData.value.reference = userStore.user.default_payment_reference
                  paymentData.value.value = totalPrice
                  break;
                case 'mbway':
                  paymentData.value.type = userStore.user.default_payment_type.toLowerCase()
                  paymentData.value.reference = paymentReference.value
                  paymentData.value.value = totalPrice

                  break;
                case 'visa':
                  paymentData.value.type = userStore.user.default_payment_type.toLowerCase()
                  paymentData.value.reference = paymentReference.value
                  paymentData.value.value = totalPrice

                  break;
                case 'paypal':
                  paymentData.value.type = userStore.user.default_payment_type.toLowerCase()
                  paymentData.value.reference = paymentReference.value
                  paymentData.value.value = totalPrice

                  break;

                default:
                  break;
            }
            
            /* nao da para testar pq tem https aqui so na vm
            axios.post('https://dad-202223-payments-api.vercel.app/api/payments',paymentData.value)
            .then((response) => status = response.status)
            .catch((error) => console.log(error.message))
            */
          

            order.value.customer_id = userStore.user ? userStore.user.id : null
            order.value.total_paid = totalPrice
            order.value.payment_type = paymentData.value.type.toUpperCase()
            order.value.payment_reference = paymentData.value.reference

            
            const orderItems = []
            const promises = await Promise.all(cart.value.map(item => axios.get(`orderitems/${item.id}`)))
            promises.forEach((p) => orderItems.push(p.data.data))
            order.value.order_items = orderItems
            await axios.post('orders',order.value)

            

          
      
          } catch (error) {
            console.log(error.message)
          }
          
    }
  }

  const totalPrice = computed(() => {
    cart.value.forEach(item => {
      total.value = total.value + Number(item.price)
      //console.log("preço do item: " + item.price)
      //console.log("preço do item: " + typeof item.price)
    });
    return total.value
})
  
  onMounted (() => {
    loadCart()
  })
</script>

<template>
  <div>
    <button
      class="btn btn-xs btn-info"
      @click="goBack()"
    >Back</button>
  </div>
  <h3 class="mt-5 mb-3">Payment</h3>
  <hr>
  <product-table
    :products="cart"
    :showId="false"
    :showEditButton="false"
    :showDeleteButton="false"
    :showRemoveFromCartButton="false"
    @remove="removeProduct"
  ></product-table>
  <template class="paginator">
    <pagination
      v-model="page"
      :records="paginationData ? paginationData.total : 0"
      :per-page="paginationData ? paginationData.per_page : 0"
      @paginate="loadCart"
      :options="{hideCount: true}">
    </pagination>
  </template>
  <h3 class="mt-5 mb-3">Total: {{totalPrice}}</h3>
    <hr>
      <form
        class="row g-3 needs-validation"
        novalidate
        @submit.prevent="pay"
      >
      <div class="mb-3">
            <label
              for="selectType"
              class="form-label"
            >Payment Method:</label>
            <select
              class="form-select"
              id="selectDefaultPaymentType"
              v-model="paymentMethod"
            >
              <option value="default">Default payment type</option>
              <option value="visa">Visa</option>
              <option value="paypal">Paypal</option>
              <option value="mbway">MBWay</option>
            </select>
      </div>
      <div class="mb-3" v-if="paymentMethod != 'default'">
          <label
            for="inputDefaultPaymentReference"
            class="form-label"
          >Payment Reference</label>
          <input
            type="text"
            class="form-control"
            id="inputDefaultPaymentReference"
            required
            v-model="paymentReference"
          >
     </div>
      <div class="mb-3 d-flex justify-content-center">
        <button
          type="button"
          class="btn btn-primary px-5"
          @click="pay"
        >Pay</button>
      </div>
    </form>
</template>


<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addtask {
  margin-top: 1.85rem;
}

.paginator {
  display: flex;
  justify-content: center;
}
</style>
