<script setup>
import axios from "axios";
import { ref, watch, computed, inject, toDisplayString } from "vue";
import avatarNoneUrl from "@/assets/avatar-none.png";

const serverBaseUrl = inject("serverBaseUrl");
const toast = inject("toast");

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false,
  },
});

const emit = defineEmits(["save", "cancel", "photo"]);

const editingProduct = ref(props.product);
let photoUrl = ref("");

watch(
  () => props.product,
  (newProduct) => {
    editingProduct.value = newProduct;
  }
  //(newPhotoUrl)
);

const photoFullUrl = computed(() => {
  return photoUrl.value == ""
    ? editingProduct.value.photo_url
      ? serverBaseUrl + "/storage/products/" + editingProduct.value.photo_url
      : avatarNoneUrl
    : photoUrl.value;
});

const imageChange = (event) => {
  // console.log(' evento ' + event.target.files[0]== null);
  editingProduct.value.photo_file = event.target.files[0];
  console.log("Selected file: ", editingProduct.value.photo_file);

  photoUrl = URL.createObjectURL(editingProduct.value.photo_file);
  console.log("URL: " + photoUrl);
  return photoUrl;
};

const save = () => {
  emit("save", editingProduct.value);
};

const cancel = () => {
  emit("cancel", editingProduct.value);
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">Product #{{ editingProduct.id }}</h3>
    <hr />
    <div class="d-flex flex-wrap justify-content-between">
      <div class="w-75 pe-4">
        <div class="mb-3">
          <label for="inputName" class="form-label">Name</label>
          <input
            type="text"
            class="form-control"
            id="inputName"
            placeholder="Name"
            required
            v-model="editingProduct.name"
          />
          <field-error-message
            :errors="errors"
            fieldName="name"
          ></field-error-message>
        </div>

        <div class="mb-3 px-1">
          <label for="inputDescription" class="form-label">Description</label>
          <input
            type="text"
            class="form-control"
            id="inputEmail"
            placeholder="Description"
            required
            v-model="editingProduct.description"
          />
          <field-error-message
            :errors="errors"
            fieldName="description"
          ></field-error-message>
        </div>

        <div class="mb-3 px-1">
          <label for="inputPrice" class="form-label">Price</label>
          <input
            type="text"
            class="form-control"
            id="inputPrice"
            placeholder="Price"
            required
            v-model="editingProduct.price"
          />
          <field-error-message
            :errors="errors"
            fieldName="price"
          ></field-error-message>
        </div>

        <div class="mb-3">
          <label for="selectType" class="form-label">Type:</label>
          <select
            class="form-select"
            id="selectType"
            v-model="editingProduct.type"
          >
            <option value="hot dish">Hot dish</option>
            <option value="cold dish">Cold dish</option>
            <option value="drink">Drink</option>
            <option value="dessert">Dessert</option>
          </select>
        </div>
      </div>
      <div class="w-25">
        <div class="mb-3">
          <label class="form-label">Photo</label>
          <div class="form-control text-center">
            <img :src="photoFullUrl" class="w-100" />
          </div>
          <div class="form-control text-center">
            <input
              type="file"
              accept="image/*"
              class="form-control-file"
              id="actual-btn"
              v-on:change="imageChange"
            />
            <!--<label class="btn-new-one" @click="imageUpload">Upload a photo</label>      -->
          </div>
        </div>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">
        Save
      </button>
      <button type="button" class="btn btn-light px-5" @click="cancel">
        Cancel
      </button>
    </div>
  </form>
</template>

<style scoped>
.btn-new-one {
  background-color: #0d6efd;
  color: white;
  padding: 0.5rem;
  font-family: sans-serif;
  border-radius: 0.3rem;
  cursor: pointer;
  margin-top: 1rem;
}
</style>
