<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  import { useUserStore } from "../../stores/user.js"
  
  const axios = inject('axios')
  const toast = inject('toast')
  const router = useRouter()

  const cart = ref([])
  const userStore = useUserStore()

  const loadCart = () => {
      cart.value = userStore.loadCartFromLocalStorage()
  }

  const removeProduct = (product) => {
    userStore.removeProductFromCart(product)
    toast.success(`Product '${product.name}' removed from cart`)
    loadCart()
  }

  const emptyCart = () => {
   userStore.emptyCart()
   loadCart()
  }

  const payment = () => {
    router.push({ name: 'ProductsPayment'})
  }
  
  onMounted (() => {
    loadCart()
  })
</script>

<template>
  <div>
    <button
      class="btn btn-xs btn-danger"
      @click="emptyCart"
    >Empty cart</button>
  </div>
  <h3 class="mt-5 mb-3">Cart</h3>
  <hr>
  <product-table
    :products="cart"
    :showId="false"
    :showEditButton="false"
    :showDeleteButton="false"
    :showRemoveFromCartButton="true"
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
    <div>
    <button
      class="btn btn-xs btn-success"
      @click="payment"
    >Payment</button>
  </div>
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
