<script setup>
  const props = defineProps({
    projects: {
      type: Array,
      default: () => [],
    },
    showId: {
      type: Boolean,
      default: true,
    },
    showResponsible: {
      type: Boolean,
      default: true,
    },
    showDates: {
      type: Boolean,
      default: false,
    },
    showTotalHours: {
      type: Boolean,
      default: true,
    },
    showBillInformation: {
      type: Boolean,
      default: false,
    },
    showEditButton: {
      type: Boolean,
      default: true,
    },
    showDeleteButton: {
      type: Boolean,
      default: true,
    }
  })

  const emit = defineEmits(['edit', 'delete'])

  const editClick = (project) => {
      emit('edit', project)
  }

  const deleteClick = (project) => {
      emit('delete', project)
  }
</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">#</th>
        <th>Name</th>
        <th>Status</th>
        <th v-if="showResponsible">Responsible</th>
        <th v-if="showDates">Preview Start Date</th>
        <th v-if="showDates">Preview End Date</th>
        <th v-if="showDates">Real Start Date</th>
        <th v-if="showDates">Real End Date</th>
        <th v-if="showTotalHours">Total Hours</th>
        <th v-if="showBillInformation">Billed</th>
        <th v-if="showBillInformation">Total Price</th>
        <th v-if="showEditButton || showDeleteButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="project in projects"
        :key="project.id"
      >
        <td v-if="showId">{{ project.id }}</td>
        <td>{{ project.name }}</td>
        <td>{{ project.status_name }}</td>
        <td v-if="showResponsible">{{ project.responsible_name }}</td>
        <td v-if="showDates">{{ project.preview_start_date }}</td>
        <td v-if="showDates">{{ project.preview_end_date }}</td>
        <td v-if="showDates">{{ project.real_start_date }}</td>
        <td v-if="showDates">{{ project.real_end_date }}</td>
        <td v-if="showTotalHours">{{ project.total_hours }}</td>
        <td v-if="showBillInformation">{{ project.billed }}</td>
        <td v-if="showBillInformation">{{ project.total_price }}</td>
        <td
          class="text-end"
          v-if="showEditButton || showDeleteButton"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-light"
              @click="editClick(project)"
              v-if="showEditButton"
            ><i class="bi bi-xs bi-pencil"></i>
            </button>

            <button
              class="btn btn-xs btn-light"
              @click="deleteClick(project)"
              v-if="showDeleteButton"
            ><i class="bi bi-xs bi-x-square-fill"></i>
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
</style>
