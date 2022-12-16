<script setup>
import avatarNoneUrl from '@/assets/avatar-none.png'
import { ref, watch, watchEffect, computed, inject } from "vue"

const serverBaseUrl = inject("serverBaseUrl")
const axios = inject("axios")
const toast = inject("toast")

const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
  },
  showName: {
    type: Boolean,
    default: true,
  },
  showType: {
    type: Boolean,
    default: true,
  },
  showDescription: {
    type: Boolean,
    default: false,
  },
  showPrice: {
    type: Boolean,
    default: true,
  },
  showPhoto: {
    type: Boolean,
    default: true,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
  showDeleteButton: {
    type: Boolean,
    default: true,
  },
  showAddToCartButton: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(["edit", "deleted","cart"])

const editingProducts = ref(props.products)
const productToDelete = ref(null)
const deleteConfirmationDialog = ref(null)

const productToDeleteDescription = computed(() => {
  return productToDelete.value
    ? `#${productToDelete.value.id} `
    : ""
})

watch(
  () => props.products,
  (newProducts) => {
    editingProducts.value = newProducts
  }
)

// Alternative to previous watch
// watchEffect(() => {
//   editingProducts.value = props.products
// })

const editClick = (product) => {
  emit("edit", product)
}

const addToCartClick = (product) => {
  emit("cart", product)
}

const dialogConfirmedDelete = () => {
  axios
    .delete("products/" + productToDelete.value.id)
    .then((response) => {
      emit("deleted", response.data)
      toast.info("Product " + productToDeleteDescription.value + " was deleted")
    })
    .catch((error) => {
      console.log(error)
    })
}

const photoFullUrl = (product) => {
  //console.log(product)
  return product.photo_url
    ? serverBaseUrl + "/storage/products/" + product.photo_url
    : avatarNoneUrl
}

const deleteClick = (product) => {
  productToDelete.value = product
  deleteConfirmationDialog.value.show()
}
</script>

<template>
  <confirmation-dialog
    ref="deleteConfirmationDialog"
    confirmationBtn="Delete product"
    :msg="`Do you really want to delete product ${productToDeleteDescription}?`"
    @confirmed="dialogConfirmedDelete"
  >
  </confirmation-dialog>

  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">ID</th>
        <th v-if="showPhoto">Photo</th>
        <th v-if="showName">Name</th>
        <th class="text-center" v-if="showType">Type</th>
        <th v-if="showDescription">Description</th>
        <th v-if="showPrice">Price</th>
        <th v-if="showEditButton || showDeleteButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in editingProducts" :key="product.id">
        <td v-if="showPhoto" class="align-middle">
          <img :src="photoFullUrl(product)" class="rounded img_photo" />
        </td>
        <td v-if="showId">{{ product.id }}</td>
        <td v-if="showName">{{ product.name }}</td>
        <td v-if="showType">{{ product.type }}</td>
        <td v-if="showDescription">{{ product.description }}</td>
        <td v-if="showPrice">{{ product.price }}</td>
        <td
          class="text-end"
          v-if="showEditButton || showDeleteButton || showAddToCartButton"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-light"
              @click="editClick(product)"
              v-if="showEditButton"
            >
              <i class="bi bi-xs bi-pencil"></i>
            </button>

            <button
              class="btn btn-xs btn-light"
              @click="deleteClick(product)"
              v-if="showDeleteButton"
            >
              <i class="bi bi-xs bi-x-square-fill"></i>
            </button>

            <button
              class="btn btn-xs btn-success"
              @click="addToCartClick(product)"
              v-if="showAddToCartButton"
            >
              Add to cart
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
.completed {
  text-decoration: line-through;
}

button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>