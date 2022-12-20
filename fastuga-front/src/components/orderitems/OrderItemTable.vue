<script setup>
import avatarNoneUrl from '@/assets/avatar-none.png'
import { inject } from "vue"

const serverBaseUrl = inject("serverBaseUrl")

  const props = defineProps({
    orderItems: {
      type: Array,
      default: () => [],
    },
    showId: {
      type: Boolean,
      default: false,
    },
    showOrderLocalNumber: {
      type: Boolean,
      default: true,
    },
    showStatus: {
      type: Boolean,
      default: true,
    },
    showPrice: {
      type: Boolean,
      default: false,
    },
    showPreparationBy: {
      type: Boolean,
      default: true,
    },
    showNotes: {
      type: Boolean,
      default: false,
    },
    showProductName: {
      type: Boolean,
      default: true,
    },
    showProductPhoto: {
      type: Boolean,
      default: true,
    },
    showPrepareButton: {
      type: Boolean,
      default: true,
    },
    showReadyButton: {
      type: Boolean,
      default: true,
    },
  })

  const emit = defineEmits(["prepare","ready"]);

  const prepareClick = (orderItem) => {
    emit("prepare", orderItem)
  }

    const readyClick = (orderItem) => {
    emit("ready", orderItem)
  }

  const photoFullUrl = (product) => {
  //console.log(product)
  return product.photo_url
    ? serverBaseUrl + "/storage/products/" + product.photo_url
    : avatarNoneUrl
}

</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">ID</th>
        <th v-if="showOrderLocalNumber">Order Local Number #</th>
        <th v-if="showStatus">Status</th>
        <th v-if="showPrice">Price</th>
        <th v-if="showPreparationBy">Preparation by</th>
        <th v-if="showNotes">Notes</th>
        <th v-if="showProductName">Product</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="orderItem in orderItems"
        :key="orderItem.id"
      >
        <td v-if="showId">{{ orderItem.id }}</td>
        <td v-if="showOrderLocalNumber">{{ orderItem.order_local_number }}</td>
        <td v-if="showStatus">{{ orderItem.status }}</td>
        <td v-if="showPrice">{{ orderItem.price }}</td>
        <td v-if="showPreparationBy">{{ orderItem.preparation_by == null ? "-" : orderItem.preparation_by }}</td>
        <td v-if="showNotes">{{ orderItem.notes }}</td>
        <th v-if="showProductName">{{ orderItem.product.name }}</th>
        <td v-if="showProductPhoto" class="align-middle">
            <img :src="photoFullUrl(orderItem.product)" class="rounded img_photo" />
        </td>
        <td
          class="text-end"
          v-if="showPrepareButton || showReadyButton"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-info"
              @click="prepareClick(orderItem)"
              v-if="showPrepareButton && orderItem.status == 'W'"
            >Prepare
            </button>
          </div>
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-info"
              @click="readyClick(orderItem)"
              v-if="showReadyButton && orderItem.status == 'P'"
            >Ready
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

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>
