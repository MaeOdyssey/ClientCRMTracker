<template>
  <!-- Card-style container with white background and shadow -->
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <!-- Header section with title and icon -->
    <div class="px-6 py-5 border-b border-gray-200">
      <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
        <ClockIcon class="h-5 w-5 text-indigo-500 mr-2" />
        Recent Activity
      </h3>
    </div>

    <!-- Activity list with dividers between items -->
    <div class="divide-y divide-gray-200">
      <!-- Combined Timeline Items -->
      <div v-for="(item, index) in combinedTimeline" :key="index" class="px-6 py-4">
        <div class="flex">
          <!-- Icon Column - Different colors for different activity types -->
          <div class="flex-shrink-0 mr-4">
            <div class="flex items-center justify-center h-10 w-10 rounded-full"
              :class="{
                'bg-indigo-100': item.type === 'session',
                'bg-green-100': item.type === 'payment'
              }">
              <!-- Dynamic icon component based on activity type -->
              <component 
                :is="item.type === 'session' ? CalendarIcon : CreditCardIcon"
                class="h-5 w-5"
                :class="{
                  'text-indigo-500': item.type === 'session',
                  'text-green-500': item.type === 'payment'
                }" />
            </div>
          </div>
          
          <!-- Content Column -->
          <div class="flex-1 min-w-0">
            <!-- Activity header with type and date -->
            <div class="flex justify-between">
              <p class="text-sm font-medium truncate">
                {{ item.type === 'session' ? 'Session' : 'Payment' }}
              </p>
              <p class="text-xs text-gray-500 ml-2">
                {{ formatDate(item.date || item.created_at) }}
              </p>
            </div>
            
            <!-- Session-specific details -->
            <div v-if="item.type === 'session'" class="mt-1">
              <p class="text-sm text-gray-600">
                Fee: <span class="font-semibold">${{ item.fee.toFixed(2) }}</span>
              </p>
            </div>
            
            <!-- Payment-specific details -->
            <div v-else class="mt-1">
              <p class="text-sm text-gray-600">
                Client: <span class="font-semibold">${{ item.client_paid.toFixed(2) }}</span>
                <span class="mx-2">•</span>
                Insurance: <span class="font-semibold">${{ item.insurance_paid.toFixed(2) }}</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Empty State - Shows when no activity exists -->
      <div v-if="!combinedTimeline.length" class="px-6 py-12 text-center">
        <p class="text-gray-500">No recent activity found</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ClockIcon, CalendarIcon, CreditCardIcon } from '@heroicons/vue/24/outline'
import { computed } from 'vue'

const props = defineProps({
  // Array of session objects
  sessions: {
    type: Array,
    default: () => []
  },
  // Array of payment objects
  payments: {
    type: Array,
    default: () => []
  },
  // Maximum number of items to display
  maxItems: {
    type: Number,
    default: 5
  }
})

/**
 * Formats a date string into a more readable format
 * Example: "Jun 15, 3:30 PM"
 * @param {string} dateString - ISO date string
 * @returns {string} Formatted date string
 */
const formatDate = (dateString) => {
  const options = { 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }
  return new Date(dateString).toLocaleDateString(undefined, options)
}

/**
 * Computed property that combines and sorts sessions and payments
 * Returns a unified timeline of recent activities
 */
const combinedTimeline = computed(() => {
  // Map sessions with type and sortDate
  const sessionItems = props.sessions.slice(0, props.maxItems).map(session => ({
    ...session,
    type: 'session',
    sortDate: new Date(session.date) // Convert to Date object for sorting
  }))
  
  // Map payments with type and sortDate
  const paymentItems = props.payments.slice(0, props.maxItems).map(payment => ({
    ...payment,
    type: 'payment',
    sortDate: new Date(payment.created_at) // Convert to Date object for sorting
  }))
  
  // Combine, sort by date (newest first), and limit to maxItems
  return [...sessionItems, ...paymentItems]
    .sort((a, b) => b.sortDate - a.sortDate)
    .slice(0, props.maxItems)
})
</script>

<!-- 
  Potential Improvements:
  1. Add loading state while data is being fetched
  2. Include client names in the activity items
  3. Add click handlers to navigate to related records
  4. Implement infinite scrolling for longer activity lists
  5. Add visual indicators for different payment statuses
  6. Include session duration information
  7. Add filtering options (sessions only, payments only)
  8. Improve accessibility with proper ARIA attributes
  9. Add hover effects for better interactivity
  10. Include more detailed date formatting (relative time like "2 hours ago")
-->