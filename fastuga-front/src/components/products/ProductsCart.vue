<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  import { useUserStore } from "../../stores/user.js"
  
  const axios = inject('axios')
  const router = useRouter()

  const cart = ref([])
  const userStore = useUserStore()

  const loadCart = () => {
      cart.value = JSON.parse(localStorage.getItem(userStore.user.id))
      console.log("cart in productsCart: " + cart.value)
  }
  
  onMounted (() => {
    loadCart()
  })
</script>

<template>
  <h3 class="mt-5 mb-3">Cart</h3>
  <hr>
  <product-table
    :products="cart"
    :showId="false"
    :showEditButton="false"
    :showDeleteButton="false"
  ></product-table>
  <template class="paginator">
    <pagination
      v-model="page"
      :records="paginationData ? paginationData.total : 0"
      :per-page="paginationData ? paginationData.per_page : 0"
      @paginate="loadProducts"
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
.btn-addtask {
  margin-top: 1.85rem;
}

.paginator {
  display: flex;
  justify-content: center;
}
</style>
