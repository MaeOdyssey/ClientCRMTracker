<template>
  <!-- 
    Modal overlay - fixed position covering the entire viewport with semi-transparent dark background.
    Uses Tailwind's flex utilities to center the content.
  -->
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <!-- 
      Main modal container - white background with rounded corners and shadow.
      Constrained to a maximum width for better readability on larger screens.
    -->
    <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-xl">
      <!-- Client name header - prominently displayed in purple -->
      <h2 class="text-2xl font-bold text-purple-600 mb-4">
        {{ client.first_name }} {{ client.last_name }}
      </h2>
      
      <!-- 
        Two-column grid layout for contact info and stats.
        Gap-4 creates consistent spacing between grid items.
      -->
      <div class="grid grid-cols-2 gap-4 mb-4">
        <!-- Left column: Contact information -->
        <div>
          <!-- Email with icon - falls back to 'No email' if undefined -->
          <p class="text-gray-600 flex items-center">
            <EnvelopeIcon class="h-4 w-4 mr-2" />
            {{ client.email || 'No email' }}
          </p>
          <!-- Phone with icon - falls back to 'N/A' if undefined -->
          <p class="text-gray-600 flex items-center mt-2">
            <PhoneIcon class="h-4 w-4 mr-2" />
            {{ client.phone || 'N/A' }}
          </p>
        </div>
        
        <!-- 
          Right column: StatsWidget showing key metrics.
          Note: The balance is calculated as (total_payments - total_sessions).
          Fallback to 0 prevents NaN errors if these values are undefined.
        -->
        <StatsWidget
          :total-sessions="client.total_sessions || 0"
          :total-payments="client.total_payments || 0"
          :balance="(client.total_payments || 0) - (client.total_sessions || 0)"
        />
      </div>

      <!-- 
        Notes section - only shown if client.notes exists.
        Styled with light indigo background for visual distinction.
      -->
      <div v-if="client.notes" class="bg-indigo-50 p-4 rounded-lg mb-4">
        <p class="text-gray-700 italic">{{ client.notes }}</p>
      </div>

      <!-- 
        Action buttons at the bottom.
        Uses flexbox to space buttons evenly across the container.
      -->
      <div class="flex justify-between items-center mt-6">
        <!-- 
          Router link to payments page for this specific client.
          btn-primary class gives it a prominent visual treatment.
        -->
        <router-link
          :to="`/clients/${client.id}/payments`"
          class="btn-primary"
        >
          <CreditCardIcon class="h-4 w-4 mr-2" />
          View Payments
        </router-link>
        
        <!-- 
          Close button - emits 'close' event to parent component.
          btn-secondary gives it a less prominent, text-based style.
        -->
        <button
          @click="$emit('close')"
          class="btn-secondary"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
// Components
import StatsWidget from './StatsWidget.vue'
// Icons from Heroicons (only the outline variants needed for this component)
import { EnvelopeIcon, PhoneIcon, CreditCardIcon } from '@heroicons/vue/24/outline'

// Props definition
defineProps({
  client: {
    type: Object,
    required: true  // This component is useless without client data
  }
})

// Component emits - only the 'close' event in this case
defineEmits(['close'])
</script>

<style scoped>
/* 
  Primary button styling - used for the main action (View Payments)
  Uses Tailwind's @apply directive to combine utility classes
*/
.btn-primary {
  @apply px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 flex items-center text-sm;
}

/* 
  Secondary button styling - used for less important actions (Close)
  Text-only style with hover effect that changes color to purple
*/
.btn-secondary {
  @apply px-4 py-2 text-gray-600 hover:text-purple-600 text-sm;
}
</style>