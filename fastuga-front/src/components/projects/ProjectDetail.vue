<script setup>
  import { ref, watch, computed } from 'vue'
  
  const props = defineProps({
    project: {
      type: Object,
      required: true
    },
    errors: {
      type: Object,
      required: false
    },
    operationType: {
      type: String,
      default: 'insert'  // insert / update
    },
    users: {
      type: Array,
      required: true
    }    
  })

  const emit = defineEmits(['save', 'cancel'])

  const editingProject = ref(props.project)

  watch(
    () => props.project, 
    (newProject) => { 
      editingProject.value = newProject 
    }
  )

  const projectTitle = computed(()=>{
    if (!editingProject.value) {
        return ''
      }
      return props.operationType == 'insert' ? 'New Project' : 'Project #' + editingProject.value.id
  })

  const save = () => {
      emit('save', editingProject.value)
  }

  const cancel = () => {
      emit('cancel', editingProject.value)
  }

</script>

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3">{{ projectTitle }}</h3>
    <hr>

    <div class="mb-3">
      <label
        for="inputName"
        class="form-label"
      >Name</label>
      <input
        type="text"
        class="form-control"
        id="inputName"
        placeholder="Project Name"
        required
        v-model="editingProject.name"
      >
      <field-error-message :errors="errors" fieldName="name"></field-error-message>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputResponsible"
          class="form-label"
        >Responsible</label>
        <select
          class="form-select pe-2"
          id="inputResponsible"
          v-model="editingProject.responsible_id"
        >
          <option :value="null">-- No Responsible --</option>
          <option
            v-for="user in users"
            :key="user.id"
            :value="user.id"
          >{{user.name}}</option>
        </select>
        <field-error-message :errors="errors" fieldName="responsible_id"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputProject"
          class="form-label"
        >Status</label>
        <select
          class="form-select"
          id="inputProject"
          v-model="editingProject.status"
        >
          <option :value="null"></option>
          <option value="P">Pending</option>
          <option value="W">Work In Progress</option>
          <option value="C">Completed</option>
          <option value="I">Interrupted</option>
          <option value="D">Discarded</option>
        </select>
        <field-error-message :errors="errors" fieldName="status"></field-error-message>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputPreview_start_date"
          class="form-label"
        >Preview Start Date</label>
        <input
          type="date"
          class="form-control"
          id="inputPreview_start_date"
          placeholder="Preview Start Date"
          v-model="editingProject.preview_start_date"
        >
        <field-error-message :errors="errors" fieldName="preview_start_date"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputPreview_end_date"
          class="form-label"
        >Preview End Date</label>
        <input
          type="date"
          class="form-control"
          id="inputPreview_end_date"
          placeholder="Preview End Date"
          v-model="editingProject.preview_end_date"
        >
        <field-error-message :errors="errors" fieldName="preview_end_date"></field-error-message>
      </div>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 me-3 flex-grow-1">
        <label
          for="inputReal_start_date"
          class="form-label"
        >Real Start Date</label>
        <input
          type="date"
          class="form-control"
          id="inputReal_start_date"
          placeholder="Real Start Date"
          v-model="editingProject.real_start_date"
        >
        <field-error-message :errors="errors" fieldName="real_start_date"></field-error-message>
      </div>

      <div class="mb-3 ms-xs-3 flex-grow-1">
        <label
          for="inputReal_end_date"
          class="form-label"
        >Real End Date</label>
        <input
          type="date"
          class="form-control"
          id="inputReal_end_date"
          placeholder="Real End Date"
          v-model="editingProject.real_end_date"
        >
        <field-error-message :errors="errors" fieldName="real_end_date"></field-error-message>
      </div>
    </div>

    <div class="mb-3">
      <label
        for="inputTotalHours"
        class="form-label"
      >Total Hours</label>
      <input
        type="number"
        class="form-control"
        id="inputTotalHours"
        v-model="editingProject.total_hours"
      >
      <field-error-message :errors="errors" fieldName="total_hours"></field-error-message>
    </div>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 checkBilled">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="editingProject.billed"
            id="inputBilled"
          >
          <label
            class="form-check-label"
            for="inputBilled"
          >
            Project is Billed
          </label>
          <field-error-message :errors="errors" fieldName="billed"></field-error-message>
        </div>
      </div>
      <div
        class="row mb-3 total_price"
        v-show="editingProject.billed"
      >
        <label
          for="inputTotalPrice"
          class="col-sm-3 col-form-label"
        >Total Price</label>
        <div class="col-sm-9">
          <input
            type="number"
            class="form-control"
            id="inputTotalPrice"
            v-model="editingProject.total_price"
          >
          <field-error-message :errors="errors" fieldName="total_price"></field-error-message>
        </div>
      </div>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="save"
      >Save</button>
      <button
        type="button"
        class="btn btn-light px-5"
        @click="cancel"
      >Cancel</button>
    </div>

  </form>
</template>

<style scoped>
.total_price {
  width: 26rem;
}
.checkBilled {
  margin-top: 0.4rem;
  min-height: 2.375rem;
}
</style>
