<script setup>
  import { ref, onMounted, inject, toDisplayString } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  import { useUserStore } from "../../stores/user.js"
  
  const axios = inject('axios')
  const toast = inject('toast')
  const router = useRouter()

  const products = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const filterByType = ref("all")
  const userStore = useUserStore()

  const loadProducts = () => {
    const getProductsUrl = filterByType.value == "all" ? `products?page=${page.value}` : `products/${filterByType.value}?page=${page.value}`
    axios.get(getProductsUrl)
      .then((response) => {
        products.value = response.data.data
        paginationData.value = response.data.meta
      })
      .catch((error) => {
        products.value = []
        console.log(error.message)
      })
  }

  const resetPage = () => {
    page.value = 1
    loadProducts()
  }

  const addProductToCart = (product) => {
    userStore.addProductToCart(product)    
    toast.success(`Product '${product.name}' added to cart`) 
  }

  onMounted (() => {
    loadProducts()
  })
</script>

<template>
      <h3 class="mt-5 mb-3">{{ userStore.user?.type == "C" ? "Menu" : "Products" }}</h3>
      <div class="mx-2 mt-2 flex-grow-1">
      <label
        for="selectType"
        class="form-label"
      >Filter by product type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByType"
        @change="resetPage"
      >
        <option value="all">All</option>
        <option value="hotdishes">Hot dishes</option>
        <option value="colddishes">Cold dishes</option>
        <option value="drinks">Drinks</option>
        <option value="desserts">Desserts</option>
      </select>
    </div>
  <hr>
  <product-table
    :products="products"
    :showId="false"
    :showEditButton="false"
    :showDeleteButton="false"
    :showAddToCartButton="true"
    @cart="addProductToCart"
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
