<script setup>
  import { ref, computed, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import ProductTable from "./ProductTable.vue"
  
  const axios = inject('axios')
  const router = useRouter()

  const products = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const filterByType = ref("all")

  const loadProducts = () => {
    const getProductsUrl = filterByType.value == "all" ? `products?page=${page.value}` : `products/${filterByType.value}?page=${page.value}`
    axios.get(getProductsUrl)
      .then((response) => {
        //console.log(response.data)
        products.value = response.data.data
        paginationData.value = response.data.meta
        //console.log(products.value)
      })
      .catch((error) => {
        products.value = []
        console.log(error)
      })
  }

  const resetPage = () => {
    page.value = 1
    loadProducts()
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
  })

  const filteredProducts = computed( () => {
   // console.log(products.value)
   switch (filterByType.value) {
     case value:
       
       break;
   
     default:
       break;
   }
    if(filterByType.value == "all") return products.value
    return products.value.filter((product) => (filterByType.value == product.type))
  })*/
  
  onMounted (() => {
    loadProducts()
  })
</script>

<template>
      <h3 class="mt-5 mb-3">Products</h3>
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
    @edit="editProduct"
    @deleted="deletedProduct"
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
