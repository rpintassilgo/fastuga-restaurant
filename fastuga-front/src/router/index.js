import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "../stores/user.js"

import HomeView from '../views/HomeView.vue'
import Menu from '../views/Menu.vue'
import Carrinho from '../views/Carrinho.vue'
import ProdutosMenu from '../views/ProdutosMenu.vue'

import Dashboard from "../components/Dashboard.vue"
import Login from "../components/auth/Login.vue"
import Register from "../components/auth/Register.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
import Products from "../components/products/Products.vue"
//import Projects from "../components/projects/Projects.vue"
import Users from "../components/users/Users.vue"
import User from "../components/users/User.vue"

import Product from "../components/products/Product.vue"
//import Project from "../components/projects/Project.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/users',
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
      name: 'Menu',
      component: Menu
    },
    {
      path: '/Carrinho',
      name: 'Carrinho',
      component: Carrinho
    },
    {
      path: '/ProdutosMenu',
      name: 'ProdutosMenu',
      component: ProdutosMenu
    },
    {
      path: '/products',
      name: 'Products',
      component: Products,
    },
    /*{
      path: '/products/current',
      name: 'CurrentProducts',
      component: ,
      props: { onlyCurrentTasks: true, tasksTitle: 'Pedidos Atuais' }
    },*/
    /*{
      path: '/projects',
      name: 'Projects',
      component: Projects,
    },
    {
      path: '/projects/new',
      name: 'NewProject',
      component: Project,
      props: { id: -1 }
    },
    {
      path: '/projects/:id',
      name: 'Project',
      component: Project,
      props: route => ({ id: parseInt(route.params.id) })     
    },*/
    {
      path: '/users',
      name: 'Users',
      component: Users,
    },
    {
      path: '/users/:id',
      name: 'User',
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: route => ({ id: parseInt(route.params.id) })
    }, 
   /* {
      path: '/projects/:id/tasks',
      name: 'ProjectTasks',
      component: ProjectTasks,
      props: route => ({ id: parseInt(route.params.id) })
    },
    {
      path: '/projects/:id/tasks/new',
      name: 'NewTaskOfProject',
      component: Task,
      props: route => ({ id:-1, fixedProject:  parseInt(route.params.id) })
    },*/
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
      path: '/reports',
      name: 'Reports',
      component: () => import('../views/AboutView.vue')
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
    if ((userStore.user.type == 'A') || (userStore.user.id == to.params.id)) {
      next()
      return
    }
    next({ name: 'home' })
    return
  }
  next()
})

export default router
