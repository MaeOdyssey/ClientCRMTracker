<template>
  <!-- Card-style container -->
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-200">
      <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
        <CalendarIcon class="h-5 w-5 text-indigo-500 mr-2" />
        Add New Session
      </h3>
    </div>

    <!-- Form -->
    <form @submit.prevent="submitForm" class="px-6 py-5">
      <!-- Session Date -->
      <div class="mb-6">
        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
          Session Date
        </label>
        <input
          id="date"
          v-model="form.date"
          type="date"
          required
          class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
        />
      </div>

      <!-- Session Fee -->
      <div class="mb-6">
        <label for="fee" class="block text-sm font-medium text-gray-700 mb-1">
          Session Fee
        </label>
        <div class="mt-1 relative rounded-md shadow-sm">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">$</span>
          </div>
          <input
            id="fee"
            v-model.number="form.fee"
            type="number"
            step="0.01"
            min="0"
            required
            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
            placeholder="0.00"
          />
        </div>
      </div>

      <!-- Optional Notes -->
      <div class="mb-6">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
          Notes (Optional)
        </label>
        <textarea
          id="notes"
          v-model="form.notes"
          rows="3"
          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
        ></textarea>
      </div>

      <!-- Actions -->
      <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <button
          type="button"
          @click="$emit('close')"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <XMarkIcon class="h-5 w-5 mr-1" />
          Cancel
        </button>
        <button
          type="submit"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <CheckIcon class="h-5 w-5 mr-1" />
          Save Session
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '../api'
import {
  CalendarIcon,
  XMarkIcon,
  CheckIcon
} from '@heroicons/vue/24/outline'

// Props: client ID passed in
const props = defineProps({
  clientId: {
    type: String,
    required: true
  }
})

// Emits: communicate back to parent
const emit = defineEmits(['close', 'saved'])

// Session form state
const form = ref({
  client_id: props.clientId,
  date: '',
  fee: '',
  notes: ''
})

// Submit handler
async function submitForm() {
  try {
    const response = await api.post('/sessions', {
      client_id: form.value.client_id,
      date: form.value.date,
      fee: parseFloat(form.value.fee),
      notes: form.value.notes
    })

    if (response.status === 201) {
      emit('saved')
      form.value = {
        ...form.value,
        date: new Date().toISOString().split('T')[0],
        fee: '',
        notes: ''
      }
    }
  } catch (error) {
    console.error('Session creation failed:', error)
    // Optional: trigger toast/alert here
  }
}
</script>

<!-- 
💡 Potential Enhancements:
- Show loading state on submit
- Field validation (future dates, min fee, etc.)
- ARIA roles + accessible labeling
- Default today’s date on mount
- Dropdown for session types (consult, therapy, etc.)
- Add time field or duration
- Attach session to therapist ID if needed
-->
