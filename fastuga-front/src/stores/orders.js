import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useUserStore } from "./user.js"

export const useOrdersStore = defineStore('orders', () => {
    const userStore = useUserStore()

    const axios = inject('axios')
    //const socket = inject('socket')

    
    async function insertOrder(newOrder) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the insertOrder
        const response = await axios.post('orders', newOrder)
        //socket.emit('newOrder', response.data.data)
        return response.data
    }

    async function changeStatusOrder(id,status) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the changeStatusOrder
            const response = await axios.put('orders/' + id + '/' + status)
           //socket.emit('changeStatusOrder', response.data.data)
    }

    
    return { /*orders, totalOrders, getOrdersByFilter, getOrdersByFilterTotal, 
        clearOrders,*/ insertOrder, changeStatusOrder }
})
