import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import PaymentsPage from '@/views/PaymentsPage.vue'
import ClientsPageView from '@/views/ClientsPageView.vue'
import DashboardView from '../views/DashboardView.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    
    {
      path: '/clients/:id/dashboard',
      name: 'ClientDashboard',
      component: () => import('../views/ClientDashboardView.vue')
    },    

    {
      path: '/clients',
      name: 'Clients',
      component: ClientsPageView
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: () => import('../views/DashboardView.vue')
    },    
    {
      path: '/clients/:id/payments',
      name: 'ClientPayments',
      component: () => import('../views/ClientPaymentsPageView.vue')
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },

    {
      path: '/payments',
      name: 'Payments',
      component: PaymentsPage
    }
  ],
})

export default router
