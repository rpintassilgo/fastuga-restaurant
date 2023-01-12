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
    
    return { product, productId, productPhotoUrl }
})
