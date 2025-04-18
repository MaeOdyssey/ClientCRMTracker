<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Link -->
    <router-link
      to="/clients"
      class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900 mb-6"
    >
      <ArrowLeftIcon class="h-5 w-5 mr-1" />
      Back to Clients
    </router-link>

    <!-- Client Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
      <div class="flex-1 min-w-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight truncate">
          {{ client.first_name }} {{ client.last_name }}
        </h1>
        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
          <div class="flex items-center truncate">
            <EnvelopeIcon class="h-4 w-4 mr-1.5" />
            <span class="truncate">{{ client.email || 'No email' }}</span>
          </div>
          <div class="flex items-center">
            <PhoneIcon class="h-4 w-4 mr-1.5" />
            {{ client.phone || 'N/A' }}
          </div>
        </div>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <button @click="showSessionForm = true" class="btn-indigo w-full sm:w-auto">
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" /> Add Session
        </button>
        <button @click="showPaymentForm = true" class="btn-purple w-full sm:w-auto">
          <CreditCardIcon class="-ml-1 mr-2 h-5 w-5" /> Add Payment
        </button>
      </div>
    </div>

    <!-- Notes -->
    <div v-if="client.notes" class="bg-indigo-50 rounded-lg p-4 mb-8">
      <div class="flex">
        <InformationCircleIcon class="h-5 w-5 text-indigo-400" />
        <div class="ml-3">
          <h3 class="text-sm font-medium text-indigo-800">Client Notes</h3>
          <p class="mt-1 text-sm text-indigo-700">{{ client.notes }}</p>
        </div>
      </div>
    </div>

    <!-- Balance Widget -->
    <BalanceWidgetComponent
      :total-sessions="sessions.length"
      :total-charged="totalCharges"
      :total-client-paid="totalClientPayments"
      :total-insurance-paid="totalInsurancePayments"
    />

    <!-- Sessions -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-900">Sessions</h2>
      </div>
      <div class="bg-white shadow rounded-lg overflow-x-auto">
        <ul class="divide-y divide-gray-200">
          <li v-for="session in sessions" :key="session.id" class="px-4 py-4 sm:px-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
              <div class="flex items-center">
                <CalendarIcon class="h-5 w-5 text-gray-400" />
                <p class="ml-2 text-sm font-medium text-indigo-600">{{ formatDate(session.date) }}</p>
              </div>
              <p class="px-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800 w-fit">
                ${{ Number(session.fee).toFixed(2) }}
              </p>
            </div>
          </li>
          <li v-if="!sessions.length" class="px-4 py-4 sm:px-6 text-center text-gray-500">
            No sessions found
          </li>
        </ul>
      </div>
    </div>

    <!-- Payments -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-900">Payments</h2>
      </div>
      <div class="bg-white shadow rounded-lg overflow-x-auto">
        <ul class="divide-y divide-gray-200">
          <li v-for="payment in payments" :key="payment.id" class="px-4 py-4 sm:px-6">
            <div class="flex flex-col gap-2">
              <div class="flex items-center">
                <CreditCardIcon class="h-5 w-5 text-gray-400" />
                <p class="ml-2 text-sm font-medium text-purple-600">
                  {{ formatDate(payment.created_at) }}
                </p>
              </div>
              <div class="flex flex-col sm:flex-row sm:justify-between gap-2 text-sm text-gray-500">
                <p class="flex items-center">
                  <UserIcon class="mr-1.5 h-4 w-4 text-gray-400" />
                  Client: ${{ Number(payment.client_paid).toFixed(2) }}
                </p>
                <p class="flex items-center">
                  <ShieldCheckIcon class="mr-1.5 h-4 w-4 text-gray-400" />
                  Insurance: ${{ Number(payment.insurance_paid).toFixed(2) }}
                </p>
              </div>
            </div>
          </li>
          <li v-if="!payments.length" class="px-4 py-4 sm:px-6 text-center text-gray-500">
            No payments found
          </li>
        </ul>
      </div>
    </div>

    <!-- Session Modal -->
    <div v-if="showSessionForm" class="modal-overlay">
      <div class="modal-card max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Session</h3>
          <NewSessionForm
            :client-id="clientId"
            @close="showSessionForm = false"
            @saved="handleSessionAdded"
          />
        </div>
      </div>
    </div>

    <!-- Payment Modal -->
    <div v-if="showPaymentForm" class="modal-overlay">
      <div class="modal-card max-h-[90vh] overflow-y-auto">
        <NewPaymentForm
          :client-id="clientId"
          @close="showPaymentForm = false"
          @saved="handlePaymentAdded"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>



<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api'
import NewSessionForm from '@/components/NewSessionForm.vue'
import NewPaymentForm from '@/components/NewPaymentForm.vue'
import BalanceWidgetComponent from '@/components/BalanceWidgetComponent.vue'



import {
  ArrowLeftIcon,
  PlusIcon,
  CreditCardIcon,
  EnvelopeIcon,
  PhoneIcon,
  InformationCircleIcon,
  CalendarIcon,
  CurrencyDollarIcon,
  BanknotesIcon,
  UserIcon,
  ShieldCheckIcon
} from '@heroicons/vue/24/outline'

// Get client ID from route params
const route = useRoute()
const clientId = route.params.id

// Reactive state
const client = ref({})
const sessions = ref([])
const payments = ref([])
const showSessionForm = ref(false)
const showPaymentForm = ref(false)

// Computed properties
const totalClientPayments = computed(() => {
  return payments.value.reduce((sum, payment) => sum + Number(payment.client_paid), 0)
})

const totalInsurancePayments = computed(() => {
  return payments.value.reduce((sum, payment) => sum + Number(payment.insurance_paid), 0)
})

//Gets total charges and balance due
const totalCharges = computed(() => {
  return sessions.value.reduce((sum, s) => sum + Number(s.fee || 0), 0)
})

const balanceRemaining = computed(() => {
  return totalCharges.value - totalClientPayments.value - totalInsurancePayments.value
})


/**
 * Formats date string to readable format
 * @param {string} dateString - ISO date string
 * @returns {string} Formatted date (e.g. "Jan 1, 2023")
 */
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString(undefined, options)
}

/**
 * Fetches all client-related data (client info, sessions, payments)
 * Uses Promise.all for parallel requests
 */
async function fetchClientData() {
  try {
    const [clientRes, sessionsRes, paymentsRes] = await Promise.all([
      api.get(`/clients/${clientId}`),
      api.get('/sessions', { params: { client_id: clientId } }),
      api.get('/payments', { params: { client_id: clientId } })
    ])

    client.value = clientRes.data
    sessions.value = sessionsRes.data
    payments.value = paymentsRes.data
  } catch (error) {
    console.error('Error fetching client data:', error)
    // Note: In production, add user notification
  }
}

/**
 * Handles successful session addition
 * 1. Closes modal
 * 2. Refreshes data
 */
function handleSessionAdded() {
  showSessionForm.value = false
  fetchClientData()
}

/**
 * Handles successful payment addition
 * 1. Closes modal
 * 2. Refreshes data
 */
function handlePaymentAdded() {
  showPaymentForm.value = false
  fetchClientData()
}

// Fetch data when component mounts
onMounted(fetchClientData)
</script>

<!-- 
  Potential Improvements:
  1. Add loading states for data fetching
  2. Implement error handling with user notifications
  3. Add data filtering (date ranges, payment types)
  4. Include charts for visual data representation
  5. Add session/payment editing functionality
  6. Implement CSV export for sessions/payments
  7. Add pagination for large data sets
  8. Improve mobile responsiveness further
  9. Add session duration tracking
  10. Include appointment scheduling functionality
  11. Add document upload/management
  12. Implement client communication logs
-->