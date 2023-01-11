import { inject } from 'vue'
import { defineStore } from 'pinia'

export const useOrderItemsStore = defineStore('orderItems', () => {
    const axios = inject('axios')

    async function changeStatusOrderItem(id,status) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the changeStatusOrderItem
        const response = await axios.put('orderitems/' + id + '/' + status)
    }
    
    return { changeStatusOrderItem }
})
