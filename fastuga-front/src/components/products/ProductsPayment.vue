<script setup>
  import { ref, computed, onMounted, inject, toDisplayString } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  import { useUserStore } from "../../stores/user.js"
  import axios from 'axios'
  import { useOrdersStore } from '../../stores/orders'

  
  const toast = inject('toast')
  const axios2 = inject('axios')
  const paymentServiceUri = inject('paymentServiceUri')
  const router = useRouter()
  const userStore = useUserStore()
  const orderStore = useOrdersStore()


  const points = ref(0)
  const cart = ref([])
  const total = ref(0)
  const paymentMethod = userStore.user ? ref('default') : ref('visa')
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


  const loadCart = () => {
      cart.value = userStore.loadCartFromLocalStorage()
  }

   const goBack = () => {
      router.back()
  }

  const pay = async () => {
    let limiteReached = false
    let pointsUsed = false
    let total_paid = totalPrice.value
    let total_paid_in_points = 0
    let gainedPoints = 0
    console.log("cart payment: " + JSON.stringify(cart.value))
    if(userStore.user?.default_payment_type == "" && userStore.user?.default_payment_reference == "" && paymentMethod.value == "default"){
      toast.error("No default payment reference found")
    } else{
          try {
            if(totalPrice.value == 0) throw new Error('The cart is empty!');
          } catch (error) {
            toast.error("The cart is empty!")
            return;
          }
          try {
            if(userStore.user?.type == 'C'){
              let avaiablePoints = userStore.user.points
              if(((points.value <= avaiablePoints) || (points.value % 10 !== 0)) && points.value != 0){
                pointsUsed = true
                total_paid_in_points = (points.value/10)*5
                total_paid = totalPrice.value - total_paid_in_points


              } else{
                if(points.value != 0) toast.error("Not enough points or/and invalid number of points!")
                //throw new Error('Invalid points!');
              }
            }
            
            switch (paymentMethod.value) {
                case 'default':
                  paymentData.value.type = userStore.user.default_payment_type.toLowerCase()

                  if(paymentData.value.type == 'visa'){
                      if(total_paid > 200){
                        limiteReached = true
                        toast.error("Visa limit is 200€!")
                        throw new Error("payment limit")
                      }
                  } else if(paymentData.value.type == 'paypal'){
                      if(total_paid > 50){
                        limiteReached = true
                        toast.error("Paypal limit is 50€!")
                        throw new Error("payment limit")
                      }
                  } else{
                      if(total_paid > 10){
                        limiteReached = true
                        toast.error("MBway limit is 10€!")
                        throw new Error("payment limit")
                      }
                  }
                  paymentData.value.reference = userStore.user.default_payment_reference
                  paymentData.value.value = total_paid
                  break;
                case 'mbway':
                      if(total_paid > 10){
                        limiteReached = true
                        toast.error("MBway limit is 10€!")
                        throw new Error("payment limit")
                      }
                  paymentData.value.type = 'mbway'
                  paymentData.value.reference = paymentReference.value
                  paymentData.value.value = total_paid

                  break;
                case 'visa':
                      if(total_paid > 200){
                        limiteReached = true
                        toast.error("Visa limit is 200€!")
                        throw new Error("payment limit")
                      }
                  paymentData.value.type = 'visa'
                  paymentData.value.reference = paymentReference.value
                  paymentData.value.value = total_paid

                  break;
                case 'paypal':
                      if(total_paid > 50){
                        limiteReached = true
                        toast.error("Paypal limit is 50€!")
                        throw new Error("payment limit")
                      }
                  paymentData.value.type = 'paypal'
                  paymentData.value.reference = paymentReference.value
                  paymentData.value.value = total_paid

                  break;

                default:
                  break;
            }

            if(userStore.user){
              if(!limiteReached){
                  gainedPoints = Math.floor(totalPrice.value/10)
                  // put to add gained points
                  axios2.put(`customers/points/add/${userStore.user.id}`,{'points': gainedPoints})
                  .then((response) => {
                    console.log("response dos pontos: " + JSON.stringify(response))
                    toast.success("You have gained " + gainedPoints + " points!")
                    })
                  .catch((error) => console.log(error.message))
                  axios2.put(`customers/points/remove/${userStore.user.id}`,{'points': points.value})
                  .then((response) => {
                    console.log("response dos pontos: " + JSON.stringify(response))
                    toast.success("You have used " + points.value + " points!")
                    })
                  .catch((error) => console.log(error.message))
              }
            }
            
            // efetuar pagamento
            // 10 mbway, 50 paypal, 200 visa
            console.log("paymentData: " + JSON.stringify(paymentData.value))
            let payment_response = await axios.post(`${paymentServiceUri}/api/payments`, {
              "type": paymentData.value.type,
              "reference": paymentData.value.reference,
              "value" : paymentData.value.value
            })
            
          

            order.value.customer_id = userStore.user ? userStore.user.id : null
            order.value.total_paid = totalPrice.value
            order.value.payment_type = paymentData.value.type.toUpperCase()
            order.value.payment_reference = paymentData.value.reference

            if(userStore.user){
              if(pointsUsed){
                order.value.total_paid = total_paid
                order.value.total_paid_with_points = total_paid_in_points
                order.value.points_gained = gainedPoints
                order.value.points_used_to_pay = points.value
              }
            }

            
            const productsId = []
            cart.value.forEach((p) => productsId.push({ "product_id": p.id }))
            order.value.order_items = productsId
            let r = await axios2.post('orders',order.value)

            // -----------------------------------------------------------------
            // set order to ready if there are no hot dishes
            let noHotDishes = true
            cart.value.every((p) => {
              if(p.type == 'hot dish'){
                noHotDishes = false
                return false
              }
              return true
            })
            if(noHotDishes) orderStore.changeStatusOrder(r.data.data.id,'ready')

            // Its not a good idea to do this, since the user will have access to
            // the ready order endpoint, if I find a better way to do this I will
            // change it
            // -----------------------------------------------------------------


            toast.success("Payment successful!")
          } catch (error) {
            toast.error("Payment failed!")
            console.log(error.message)
          }
          
    }
  }

  const totalPrice = computed(() => {
    cart.value.forEach(item => {
      total.value = total.value + Number(item.price)
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
      <div class="mb-3" v-if="userStore.user?.type == 'C'">
          <label
            for="inputPoints"
            class="form-label"
          >Points:</label>
          <input
            type="text"
            class="form-control"
            id="inputPoints"
            required
            v-model="points"
          >
     </div>
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
              <option v-if="userStore.user" value="default">Default payment type</option>
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
