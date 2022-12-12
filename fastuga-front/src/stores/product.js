import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import avatarNoneUrl from '@/assets/avatar-none.png' 

export const useProductStore = defineStore('product', () => {
    const axios = inject('axios')
    const serverBaseUrl = inject('serverBaseUrl')
    
    const product = ref(null)

    const productId = computed(() => {
        return product.value?.id ?? -1
    })
    
    const productPhotoUrl = computed(() => {
        if (!product.value?.photo_url) {
            return avatarNoneUrl
        }
        return serverBaseUrl + '/storage/products/' + product.value.photo_url
    })
    

    async function loadProduct(id) {
        try {
            const response = await axios.get(`products/${id}`)
            product.value = response.data.data
        } catch (error) {
            product.value = null
            throw error
        }
    }
    
    
    return { product, productId, productPhotoUrl }
})
