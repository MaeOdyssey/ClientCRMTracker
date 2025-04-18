<template>
  <!-- Card-style container with white background and rounded corners -->
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <!-- Header section with title and icon -->
    <div class="px-6 py-5 border-b border-gray-200">
      <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
        <CreditCardIcon class="h-5 w-5 text-purple-500 mr-2" />
        Add Payment Record
      </h3>
    </div>

    <!-- Main form section -->
    <form @submit.prevent="submitForm" class="px-6 py-5">
      <!-- Client selection dropdown -->
      <div class="mb-6">
        <label for="client" class="block text-sm font-medium text-gray-700 mb-1">
          Client
        </label>
        <select
          id="client"
          v-model="form.client_id"
          required
          class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
        >
          <!-- Disabled default option -->
          <option disabled value="">Select a client</option>
          <!-- Dynamic client options -->
          <option 
            v-for="client in clients" 
            :key="client.id" 
            :value="client.id"
            class="py-1"
          >
            {{ client.first_name }} {{ client.last_name }}
          </option>
        </select>
      </div>

      <!-- Payment amount inputs in responsive grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Client payment input -->
        <div>
          <label for="client_paid" class="block text-sm font-medium text-gray-700 mb-1">
            Client Payment
          </label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <!-- Dollar sign prefix -->
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500 sm:text-sm">$</span>
            </div>
            <input
              id="client_paid"
              v-model.number="form.client_paid" 
              type="number"
              step="0.01" 
              min="0" 
              required
              class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
              placeholder="0.00"
            />
          </div>
        </div>

        <!-- Insurance payment input (same structure as client payment) -->
        <div>
          <label for="insurance_paid" class="block text-sm font-medium text-gray-700 mb-1">
            Insurance Payment
          </label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500 sm:text-sm">$</span>
            </div>
            <input
              id="insurance_paid"
              v-model.number="form.insurance_paid"
              type="number"
              step="0.01"
              min="0"
              required
              class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
              placeholder="0.00"
            />
          </div>
        </div>
      </div>

      <!-- Form action buttons -->
      <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <!-- Cancel button -->
        <button
          type="button"
          @click="$emit('close')"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <XMarkIcon class="h-5 w-5 mr-1" />
          Cancel
        </button>
        <!-- Submit button -->
        <button
          type="submit"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <CheckIcon class="h-5 w-5 mr-1" />
          Save Payment
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import {
  CreditCardIcon,
  XMarkIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

// Component props - accepts optional clientId for pre-selection
const props = defineProps({
  clientId: {
    type: String,
    default: ''
  }
})

// Component emits - communicates with parent component
const emit = defineEmits(['close', 'saved'])

// Reactive form data
const form = ref({
  client_id: props.clientId || '', // Pre-populate if clientId provided
  client_paid: '',
  insurance_paid: ''
})

// Client list for dropdown
const clients = ref([])

/**
 * Fetches clients from API and auto-selects if clientId prop is provided
 */
async function fetchClients() {
  try {
    const res = await api.get('/clients')
    clients.value = res.data
    // Auto-select client if prop provided but not already set
    if (props.clientId && !form.value.client_id) {
      form.value.client_id = props.clientId
    }
  } catch (err) {
    console.error('Could not load clients:', err)
    // Note: In production, show user feedback
  }
}

/**
 * Handles form submission:
 * 1. Posts payment data to API
 * 2. Emits 'saved' event on success
 * 3. Resets form (while preserving clientId if provided)
 */
async function submitForm() {
  try {
    await api.post('/payments', form.value)
    emit('saved') // Notify parent of successful save
    // Reset form while preserving clientId if provided
    form.value = {
      client_id: props.clientId || '',
      client_paid: '',
      insurance_paid: ''
    }
  } catch (error) {
    console.error('Failed to save payment:', error)
    // Note: In production, show user feedback
  }
}

// Fetch clients when component mounts
onMounted(fetchClients)
</script>

<!-- 
  Potential Improvements:
  1. Add form validation (minimum payment amounts, etc.)
  2. Implement loading states during API calls
  3. Add success/error notifications
  4. Include payment date field
  5. Add payment method selection (cash, card, insurance, etc.)
  6. Improve accessibility with proper ARIA attributes
  7. Add currency formatting/parsing
  8. Implement client search/filter in dropdown
-->