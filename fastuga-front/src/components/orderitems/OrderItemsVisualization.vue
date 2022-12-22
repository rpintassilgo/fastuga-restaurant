<script setup>
  import { ref, onMounted, inject } from 'vue'
  import {useRouter} from 'vue-router'
  import OrderItemTable from "./OrderItemTable.vue"

  const router = useRouter()

  const axios = inject("axios")

  const orderItems = ref([])
  const paginationData = ref(null)
  const page = ref(1)
  const filterByStatus = ref('waiting')

  const props = defineProps({
      order_id: {
          type: Number,
          default: null
      }
  })
  

 const loadOrderItems= () => {
    const getUsersUrl = filterByStatus.value == "all" ? `orderitems/order/${props.order_id}?page=${page.value}` : 
                                                `orderitems/order/${props.order_id}/${filterByStatus.value}?page=${page.value}`

    axios.get(getUsersUrl)
        .then((response) => {
          console.log("hot dishes: " + JSON.stringify(response.data.data))
          orderItems.value = response.data.data
          paginationData.value = response.data.meta
        })
        .catch((error) => {
          orderItems.value = []
          console.log(error)
        })
  }

  const resetPage = () => {
    page.value = 1
    loadOrderItems()
  }

   const goBack = () => {
      router.back()
  }

  onMounted(() => {
    loadOrderItems()
  })

</script>

<template>
      <div>
        <button
        class="btn btn-xs btn-info"
        @click="goBack()"
        >Back</button>
      </div>
      <h3 class="mt-5 mb-3">Order Items from Order #{{props.order_id}}</h3>
      <div class="mx-2 mt-2 flex-grow-1">
      <label
        for="selectType"
        class="form-label"
      >Filter by order item status:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByStatus"
        @change="resetPage"
      >
        <option value="all">All</option>
        <option value="waiting">Waiting</option>
        <option value="preparing">Preparing</option>
        <option value="ready">Ready</option>
      </select>
    </div>
  <hr>

  <order-item-table
    :orderItems="orderItems"
    :showPrepareButton="false"
    :showReadyButton="false"
  ></order-item-table>
  <template class="paginator">
    <pagination
      v-model="page"
      :records="paginationData ? paginationData.total : 0"
      :per-page="paginationData ? paginationData.per_page : 0"
      @paginate="loadHotDishes"
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
.btn-addprj {
  margin-top: 1.85rem;
}

.paginator {
  display: flex;
  justify-content: center;
}
</style>
