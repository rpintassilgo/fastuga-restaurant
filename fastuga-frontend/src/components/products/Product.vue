<script setup>
  import { ref, watch, computed, onMounted, inject } from 'vue'
  import { useRouter, onBeforeRouteLeave } from 'vue-router'  
  import { useProductStore } from "../../stores/product.js"
  import axios from 'axios'
  
  import ProductDetail from "./ProductDetail.vue"

  const router = useRouter()  
  const axios2 = inject('axios')
  const toast = inject('toast')
  const productStore = useProductStore()

  const newProduct = () => {
      return {
        id: null,
        type: '',  
        description: '',
        photo_url: '',
        photo_file: null,
        price: null
      }
  }

  const product = ref(newProduct())
  const errors = ref(null)
  const confirmationLeaveDialog = ref(null)

  let originalValueStr = ''
  const loadProduct = (id) => {
      originalValueStr = ''
      errors.value = null
      if (!id || (id < 0)) {
        product.value = newProduct()
        originalValueStr = dataString()
      } else {
        axios2.get('products/' + id)
          .then((response) => {
            product.value = response.data.data
            originalValueStr = dataString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
  }

  const imageUpload = async () => {
      let url = ""
      if(product.value.photo_file != null){
            let formData = new FormData()
            formData.append('photo_file',product.value.photo_file)
            try {
                let response = await axios.post(`${import.meta.env.VITE_APP_BASE_URL}/api/products/image`,formData, 
                {
                  headers: {
                    'Content-Type': 'multipart/form-data',
                    'Authorization': "Bearer " + sessionStorage.getItem('token')
                  }
                })

                url = response.data

            } catch (error) {
              console.log(error.message)
            }
      }
      return url
  }

  const save = async () => {

      errors.value = null
      if (operation.value == 'insert') {
        try {
            product.value.photo_url = await imageUpload()
            if(product.value.photo_url == "") throw new Error('Product was not created because no photo was uploaded!');
            axios2.post('products', product.value)
              .then((response) => {
                product.value = response.data.data
                originalValueStr = dataString()
                toast.success('Product #' + product.value.id + ' was created successfully.')
                router.back()
              })
              .catch((error) => {
                if (error.response.status == 422) {
                  toast.error('Product was not created due to validation errors!')
                  errors.value = error.response.data.errors
                } else {
                  toast.error('Product was not created due to unknown server error!')
                }
              })
          
        } catch (error) {
          toast.error('Product was not created because no photo was uploaded!');
        }
      } else {
        let newPhoto = await imageUpload()
        if( newPhoto != ""){
          product.value.photo_url = newPhoto
        }
        axios2.put('products/' + props.id, product.value)
          .then((response) => {
            product.value = response.data.data
            originalValueStr = dataString()
            toast.success('Product #' + product.value.id + ' was updated successfully.')
            router.back()
          })
          .catch((error) => {
            if (error.response.status == 422) {
              toast.error('Product #' + props.id + ' was not updated due to validation errors!')
              errors.value = error.response.data.errors
            } else {
              toast.error('Product #' + props.id + ' was not updated due to unknown server error!')
            }
          })
      }
    }
    
  const cancel = () => {
    originalValueStr = dataString()
    router.back()
  }

  const dataString = () => {
      return JSON.stringify(product.value)
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

  const operation = computed( () => (!props.id || props.id < 0) ? 'insert' : 'update')
  
    // beforeRouteUpdate was not fired correctly
    // Used this watcher instead to update the ID
  watch(
    () => props.id,
    (newValue) => {
        loadProduct(newValue)
      }, 
    { immediate: true}
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
  <product-detail
    :operationType="operation"
    :product="product"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></product-detail>
</template>