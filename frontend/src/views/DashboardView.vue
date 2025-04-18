<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with navigation -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
          📊 Dashboard Overview
        </h1>
        <p class="text-sm text-gray-500">Insights into clients, revenue, and activity</p>
      </div>
      <router-link to="/clients" class="btn-indigo">
        View All Clients
      </router-link>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <StatsCard 
        title="Total Clients" 
        :value="clientCount"
        icon="UserGroupIcon"
        color="indigo"
      />
      <StatsCard 
        title="Total Revenue" 
        :value="totalRevenue"
        icon="CurrencyDollarIcon"
        color="green"
      />
      <StatsCard 
        title="Active Sessions" 
        :value="activeSessions"
        icon="CalendarIcon"
        color="blue"
      />
    </div>

    <!-- Recent Activity -->
    <RecentActivity 
      :sessions="recentSessions" 
      :payments="recentPayments" 
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import StatsCard from '@/components/StatsWidgetComponent.vue'
import RecentActivity from '@/components/RecentActivityComponent.vue'
import {
  UserGroupIcon,
  CurrencyDollarIcon,
  CalendarIcon
} from '@heroicons/vue/24/outline'

// Reactive dashboard metrics
const clientCount = ref(0)
const totalRevenue = ref(0)
const activeSessions = ref(0)
const recentSessions = ref([])   // Last 5 sessions
const recentPayments = ref([])   // Last 5 payments

/**
 * Fetch dashboard data in parallel.
 */
async function fetchDashboardData() {
  try {
    const [clientsRes, sessionsRes, paymentsRes] = await Promise.all([
      api.get('/clients'),
      api.get('/sessions'),
      api.get('/payments')
    ])

    clientCount.value = clientsRes.data.length
    recentSessions.value = sessionsRes.data.slice(0, 5)
    recentPayments.value = paymentsRes.data.slice(0, 5)

    totalRevenue.value = paymentsRes.data.reduce(
      (sum, p) => sum + Number(p.client_paid || 0) + Number(p.insurance_paid || 0),
      0
    )
    activeSessions.value = sessionsRes.data.length
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
  }
}

onMounted(fetchDashboardData)
</script>

<style scoped>

</style>


<!-- 
✨ Potential Enhancements:
1. Add charts for trends over time (sessions/payments)
2. Show upcoming appointments or overdue payments
3. Add refresh button to manually reload data
4. Support date filters (weekly/monthly range)
5. Add loading spinners and empty-state handling
6. LocalStorage or Pinia for cached counts
7. Real-time updates via WebSockets
8. Export metrics to CSV
-->
