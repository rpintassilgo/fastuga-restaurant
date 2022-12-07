<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
  task: {
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
  },
  projects: {
    type: Array,
    required: true,
  },
  fixedProject: {
    type: Number,
    default: null,
  },
});

const emit = defineEmits(["save", "cancel"]);

const editingTask = ref(props.task);

watch(
  () => props.task,
  (newTask) => {
    editingTask.value = newTask;
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

const taskTitle = computed(() => {
  if (!editingTask.value) {
    return "";
  }
  return props.operationType == "insert" ? "Novo Pedido" : "Task #" + editingTask.value.id;
});

const save = () => {
  emit("save", editingTask.value);
};

const cancel = () => {
  emit("cancel", editingTask.value);
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">{{ taskTitle }}</h3>
    <hr />

    <div class="mb-3">
      <label for="inputDescription" class="form-label">Descrição</label>
      <input
        type="text"
        class="form-control"
        id="inputDescription"
        placeholder="Descrição do Pedido"
        required
        v-model="editingTask.description"
      />
      <field-error-message :errors="errors" fieldName="descrição"></field-error-message>
    </div>
    <div class="mb-3">
      <label for="inputNotes" class="form-label">Notas</label>
      <textarea
        class="form-control"
        id="inputNotes"
        rows="4"
        v-model="editingTask.notes"
      ></textarea>
      <field-error-message :errors="errors" fieldName="notas"></field-error-message>
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
