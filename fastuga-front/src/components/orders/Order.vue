<script setup>
  import { ref, watch, computed, onMounted, inject} from 'vue'
  import { useRouter, onBeforeRouteLeave } from 'vue-router'  
  import { useUserStore } from "../../stores/user.js"
  // ainda tenho de ver se isto vai ser preciso aqui
  //import { useProductStore } from "../../stores/product.js" 

  import OrderDetail from "./OrderDetail.vue"

  const router = useRouter()  
  const axios = inject('axios')
  const toast = inject('toast')
  const userStore = useUserStore()
  const projectsStore = useProjectsStore()
  

  const newOrder = () => { 
    return {
      id: null,
      ticket_number: null,
      status: '',  
      customer_id: userStore.userId,
      total_price: null,
      total_paid: null,
      total_paid_with_points: null,
      points_gained: null,
      points_used_to_pay: null,
      payment_type: '',
      payment_reference: '',
      date: '',
      delivered_by: null
    }
  }

  let originalValueStr = ''
  const loadOrder = (id) => {
      originalValueStr = ''
      errors.value = null
      if (!id || (id < 0)) {
        order.value = newOrder()
        originalValueStr = dataString()
      } else {
        axios.get('orders/' + id)
          .then((response) => {
            order.value = response.data
            originalValueStr = dataString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
    }

  // *
  const save = () => {
      errors.value = null
      if (operation.value == 'insert') {
        ordersStore.insertProject(order.value)
          .then((insertedOrder) => {
            order.value = insertedOrder
            originalValueStr = dataString()
            toast.success('Order #' + order.value.id + ' was created successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Order was not created due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Order was not created due to unknown server error!')
            }
          })
      } else {
        ordersStore.updateOrder(project.value)
        .then((updatedOrder) => {
            order.value = updatedOrder
            originalValueStr = dataString()
            toast.success('Order #' + order.value.id + ' was updated successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Order #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Order #' + props.id + ' was not updated due to unknown server error!')
            }
          })
      }
    }

  const cancel = () => {
    originalValueStr = dataString()
    router.back()
  }

  const dataString = () => {
      return JSON.stringify(order.value)
  }

  let nextCallBack = null
  const leaveConfirmed = () => {
      if (nextCallBack) {
        nextCallBack()
      }
  }

  onBeforeRouteLeave((to, from, next) => {
    nextCallBack = null
    let newValueStr = dataString()
    if (originalValueStr != newValueStr) {
      nextCallBack = next
      confirmationLeaveDialog.value.show()
    } else {
      next()
    }
  })  

  const props = defineProps({
      id: {
        type: Number,
        default: null
      }
    })

  const order = ref(newOrder())   
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)

  const operation = computed(() => {
    return (!props.id || props.id < 0) ? 'insert' : 'update'
  })

  watch(
    () => props.id, 
    (newValue) => {
          loadOrder(newValue)
    }, {
      immediate: true,
    }
  )
</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>  

  <OrderDetail
    :operationType="operation"
    :order="order"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></OrderDetail>
</template>
