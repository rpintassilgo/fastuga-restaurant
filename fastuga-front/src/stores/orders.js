import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import { useUserStore } from "./user.js"

export const useOrdersStore = defineStore('orders', () => {
    const userStore = useUserStore()

    const axios = inject('axios')
    const paymentServiceUri = inject('paymentServiceUri')
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
        if(status == 'deliver'){
            const response = await axios.put('orders/' + id + '/' + status,{
                delivery_id: userStore.user?.id
            })
        }else if(status == 'cancel'){
            // get order
            const res = await axios.get(`orders/${id}`)
            const order = res.data.data
            //console.log("ORDER A CANCELAR: " + JSON.stringify(order))
            console.log("ORDER A CANCELAR: " + parseFloat(order.total_paid))
            console.log("ORDER A CANCELAR: " + order.total_paid)

            // return points back to customers
            if(userStore.user){
                axios.put(`customers/points/add/${order.customer_id}`,{'points': order.points_used_to_pay })
                .then((response) => {
                  console.log("response dos pontos: " + JSON.stringify(response))
                  //toast.success("You have gained " + gainedPoints + " points!")
                  })
                .catch((error) => console.log(error.message))
                axios.put(`customers/points/remove/${order.customer_id}`,{'points': order.points_gained })
                .then((response) => {
                  console.log("response dos pontos: " + JSON.stringify(response))
                  //toast.success("You have used " + points.value + " points!")
                  })
                .catch((error) => console.log(error.message))
            }

            // fake money refund
            /*
            try {
                let payment_response = await axios.post(`${paymentServiceUri}/api/refunds`, {
                    "type": order.payment_type,
                    "reference": order.payment_reference,
                    "value" : parseFloat(order.total_paid)
                  })
            } catch (error) {
                console.log("refund: " + JSON.stringify(payment_response))
            }
            */

            const response = await axios.put('orders/' + id + '/' + status)
        }else{
            const response = await axios.put('orders/' + id + '/' + status)
        }

        //socket.emit('changeStatusOrder', response.data.data)
    }

    
    return { insertOrder, changeStatusOrder }
})
