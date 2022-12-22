import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "../stores/user.js"

import ProductsMenu from '../components/products/ProductsMenu.vue'
import ProductsPayment from '../components/products/ProductsPayment.vue'

import Dashboard from "../components/Dashboard.vue"
import Login from "../components/auth/Login.vue"
import Register from "../components/auth/Register.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
import Products from "../components/products/Products.vue"
import ProductsCart from "../components/products/ProductsCart.vue"
import ProductsVisualization from "../components/products/ProductsVisualization.vue"
import Orders from "../components/orders/Orders.vue"
import OrdersDelivery from "../components/orders/OrdersDelivery.vue"
import OrdersFromCustomer from "../components/orders/OrdersFromCustomer.vue"
import OrderItems from "../components/orderitems/OrderItems.vue"
import OrderItemsVisualization from "../components/orderitems/OrderItemsVisualization.vue"
import Users from "../components/users/Users.vue"
import User from "../components/users/User.vue"

import Product from "../components/products/Product.vue"
import Order from "../components/orders/Order.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Dashboard
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    { // customer
      path: '/register',
      name: 'Register',
      component: Register
    },

    {
      path: '/password',
      name: 'ChangePassword',
      component: ChangePassword
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/menu',
      name: 'ProductsMenu',
      component: ProductsMenu
    },
    {
      path: '/products',
      name: 'Products',
      component: Products,
    },
    {
      path: '/cart',
      name: 'ProductsCart',
      component: ProductsCart,
    },
    {
      path: '/payment',
      name: 'ProductsPayment',
      component: ProductsPayment,
    },
    {
      path: '/items',
      name: 'ProductsVisualization',
      component: ProductsVisualization,
    },
    {
      path: '/orders',
      name: 'Orders',
      component: Orders,
    },
    {
      path: '/orders-delivery',
      name: 'OrdersDelivery',
      component: OrdersDelivery,
    },
    {
      path: '/order-items',
      name: 'OrderItems',
      component: OrderItems,
    },
    {
      path: '/order-items/order/:order_id',
      name: 'OrderItemsVisualization',
      component: OrderItemsVisualization,
      props: route => ({ order_id: parseInt(route.params.order_id) }) 
    },
    {
      path: '/orders/customer/:id',
      name: 'OrdersFromCustomer',
      component: OrdersFromCustomer,
      props: route => ({ id: parseInt(route.params.id) })   // customer id
    },
    {
      path: '/orders/new', // isto aqui possivelmente vai ser o carrinho
      name: 'NewOrder',
      component: Order,
      props: { id: -1 }
    },
    {
      path: '/orders/:id',
      name: 'Order',
      component: Order,
      props: route => ({ id: parseInt(route.params.id) })     
    },
    {
      path: '/users',
      name: 'Users',
      component: Users,
    },
    {
      path: '/users/:id',
      name: 'User',
      component: User,
      props: route => ({ id: parseInt(route.params.id) })
    }, 
    { // admin and employees
      path: '/users/new',
      name: 'NewUser',
      component: User,
      props: { id: -1 }
    },
    {
      path: '/products/new',
      name: 'NewProduct',
      component: Product,
      props: { id: -1 }
    },
    {
      path: '/products/:id',
      name: 'Product',
      component: Product,
      props: route => ({ id: parseInt(route.params.id) })    
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    }
  ]
})

let handlingFirstRoute = true

router.beforeEach(async (to, from, next) => {  
  const userStore = useUserStore()  
  if (handlingFirstRoute) {
    handlingFirstRoute = false
    await userStore.restoreToken()
  }
  if ((to.name == 'Login') || (to.name == 'home') || (to.name == 'Register')) {
    next()
    return
  }
  if (!userStore.user) {
    next({ name: 'Login' })
    return
  }
  if (to.name == 'Reports') {
    if (userStore.user.type != 'A') {
      next({ name: 'home' })
      return
    }
  }
  if (to.name == 'User') {
    if ((userStore.user.type == 'EM') || (userStore.user.id == to.params.id)) {
      next()
      return
    }
    next({ name: 'home' })
    return
  }
  next()
})

export default router
