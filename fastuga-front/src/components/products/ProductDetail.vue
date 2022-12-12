<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false,
  },
  operationType: {
    type: String,
    default: "insert", // insert / update
  }
});

const emit = defineEmits(["save", "cancel"]);

const editingProduct = ref(props.product);

watch(
  () => props.product,
  (newProduct) => {
    editingProduct.value = newProduct;
  }
);

watch(
  () => props.fixedProject,
  (newFixedProject) => {
    if (newFixedProject) {
      editingTask.value.project_id = newFixedProject;
    }
  },
  { immediate: true }
);

const productTitle = computed(() => {
  if (!editingProduct.value) {
    return "";
  }
  return props.operationType == "insert" ? "New Product" : "Product #" + editingProduct.value.id;
});

const save = () => {
  emit("save", editingProduct.value);
};

const cancel = () => {
  emit("cancel", editingProduct.value);
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">{{ productTitle }}</h3> <!-- o titulo do produto é o id -->
    <hr />

    <div class="mb-3">
      <label for="inputName" class="form-label">Name</label>
      <input
        type="text"
        class="form-control"
        id="inputName"
        placeholder="Product name"
        required
        v-model="editingProduct.name"
      />
      <field-error-message :errors="errors" fieldName="name"></field-error-message>
    </div>

        <!-- possivelmente meter aqui o tipo tbm de maneira q n dê para trocar-->
        <!-- ver depois se faz sentido poder trocar o tipo -->
    <div class="mb-3">
      <label
        for="selectType"
        class="form-label"
      >Type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="editingProduct.type"
      >
        <option value="Hot dish">Hot dish</option>
        <option value="Cold dish">Cold dish</option>
        <option value="Drink">Drink</option>
        <option value="Dessert">Dessert</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="inputDescription" class="form-label">Description</label>
      <input
        type="text"
        class="form-control"
        id="inputDescription"
        placeholder="Product descripton"
        required
        v-model="editingProduct.description"
      />
      <field-error-message :errors="errors" fieldName="description"></field-error-message>
    </div>

    <!-- meter aqui a cena da foto, no entanto nao é enviar url mas sim msm a foto-->

    <div class="mb-3">
      <label for="inputPrice" class="form-label">Price</label>
      <input
        type="text"
        class="form-control"
        id="inputDescription"
        placeholder="Product price"
        required
        v-model="editingProduct.price"
      />
      <field-error-message :errors="errors" fieldName="price"></field-error-message>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-5" @click="save">Save</button>
      <button type="button" class="btn btn-light px-5" @click="cancel">Cancel</button>
    </div>
  </form>
</template>

<style scoped>
.total_hours {
  width: 26rem;
}
.checkCompleted {
  min-height: 2.375rem;
}
</style>
