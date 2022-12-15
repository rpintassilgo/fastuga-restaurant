import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useUserStore } from "./user.js"

export const useOrdersStore = defineStore('orders', () => {
    const userStore = useUserStore()

    const axios = inject('axios')
    
    const orders = ref([])

    const totalOrders = computed(() => {
        return orders.value.length
    })

    function getOrdersByFilter(customerId, status) {
        return orders.value.filter( o =>
            (!customerId || customerId == o.customer_id) &&
            (!status || status == o.status)
        )
    }
    
    function getOrdersByFilterTotal(customerId, status) {
        return getOrdersByFilter(customerId, status).length
    }

    function clearOrders() {
        orders.value = []
    }

    async function loadAllOrders() {
        try {
            const response = await axios.get('orders')
            orders.value = response.data
            return orders.value
        } catch (error) {
            clearOrders()
            throw error
        }
    }

    async function loadCustomerOrders(customerId) {
        try {
            const response = await axios.get('orders/customer' + customerId)
            orders.value = response.data
            return orders.value
        } catch (error) {
            clearOrders()
            throw error
        }
    }
    
    async function insertOrder(newOrder) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the insertOrder
        const response = await axios.post('orders', newOrder)
        orders.value.push(response.data)
        return response.data
    }

    async function changeStatusOrder(id,status) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the changeStatusOrder
            const response = await axios.put('orders/' + id + '/' + status)
            let idx = orders.value.data.findIndex((o) => o.id === response.data.data.id)
            if (idx >= 0) {
                orders.value.data[idx] = response.data.data
            }
    }

    
    return { orders, totalOrders, getOrdersByFilter, getOrdersByFilterTotal, 
        loadAllOrders, loadCustomerOrders, clearOrders, insertOrder, changeStatusOrder }
})
