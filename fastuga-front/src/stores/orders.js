import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useUserStore } from "./user.js"

export const useOrdersStore = defineStore('orders', () => {
    const userStore = useUserStore()

    const axios = inject('axios')
    const socket = inject('socket')


    // verificar se depois como tou a fazer é uma boa pratica
    // ou se devo tentar menos menos codigo nos componentes order e tudo aqui
    // e depois chamar as funcoes
    // no entanto neste caso da mais jeito ter a vars orders la 
    // ha mts coisas q se pode alterar para tornar o codigo mais clean




    /*
    
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
    }*/
    
    async function insertOrder(newOrder) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the insertOrder
        const response = await axios.post('orders', newOrder)
        socket.emit('newOrder', response.data.data)
        return response.data
    }

    async function changeStatusOrder(id,status) {
        // Note that when an error occours, the exception should be
        // catch by the function that called the changeStatusOrder
            const response = await axios.put('orders/' + id + '/' + status)

            /* nao sei se vale a pena fazer isto ja q faço um pedido quando acedo à pagina
            let idx = orders.value.data.findIndex((o) => o.id === response.data.data.id)
            if (idx >= 0) {
                orders.value.data[idx] = response.data.data
            }
            */
           socket.emit('changeStatusOrder', response.data.data)
    }

    
    return { /*orders, totalOrders, getOrdersByFilter, getOrdersByFilterTotal, 
        clearOrders,*/ insertOrder, changeStatusOrder }
})
