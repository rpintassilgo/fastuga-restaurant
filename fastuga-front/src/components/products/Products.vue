<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  
  const axios = inject('axios')
  const router = useRouter()

  const products = ref([])
  const filterByType = ref(-1)

  const loadProducts = () => {
    axios.get('products')
      .then((response) => {
        //console.log(response.data)
        products.value = response.data
        //console.log(products.value)
      })
      .catch((error) => {
        console.log(error)
      })
  }

  const addProduct = () => {
    router.push({ name: 'NewProduct'})
  }
  
  const editProduct = (task) => {
    router.push({ name: 'Product', params: { id: product.id } })
  }

  const deletedProduct = (deletedProduct) => {
      let idx = products.value.findIndex((p) => p.id === deletedProduct.id)
      if (idx >= 0) {
        products.value.splice(idx, 1)
      }
  }

  /*const props = defineProps({
    productsTitle: {
      type: String,
      default: 'Products'
    }
  })*/

  const filteredProducts = computed( () => {
   // console.log(products.value)
    return products.value.filter((product) => (filterByType.value == product.type))
  })
  
  onMounted (() => {
    loadProducts()
  })
</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <!--  <h3 class="mt-4">{{ productsTitle }}</h3>  -->
      <h3 class="mt-4">Products</h3>
    </div>
  </div>
  <hr>
  <div
    class="mb-3 d-flex justify-content-between flex-wrap"
  >
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectType"
        class="form-label"
      >Filter by product type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByType"
      >
        <option value="-1">All</option>
        <option value="0">Hot dishes</option>
        <option value="1">Cold dishes</option>
        <option value="2">Drinks</option>
        <option value="3">Desserts</option>
      </select>
    </div>

    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-success px-4"
        @click="addProduct"
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp;Add Product</button>
    </div>
  </div>
  <product-table
    :products="products"
    :showId="false"
    :showDescription="false"
    @edit="editProduct"
    @deleted="deletedProduct"
  ></product-table>
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
</style>
