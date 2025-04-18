<template>
  <!-- Main container with responsive max-width and padding -->
  <div class="max-w-5xl mx-auto px-6 py-10 space-y-10">
    <!-- Navigation Section -->
    <div class="flex justify-between items-center">
      <!-- Back navigation link -->
      <router-link
        to="/clients"
        class="inline-flex items-center px-5 py-3 text-lg text-purple-700 border border-purple-300 rounded-lg hover:bg-purple-50 transition"
      >
        ← Back to Clients
      </router-link>

      <!-- Add Payment button with gradient and hover effects -->
      <button
        @click="openNewPaymentModal"
        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-lg font-semibold rounded-full shadow hover:shadow-lg transition"
      >
        ➕ Add Payment
      </button>
    </div>

    <!-- Client Information Card -->
    <div class="bg-white p-8 rounded-xl shadow-md">
      <h2 class="text-4xl font-bold text-purple-600 mb-4">
        {{ client.first_name }} {{ client.last_name }}
      </h2>
      <!-- Contact information with emoji indicators -->
      <p class="text-lg text-gray-700">📧 {{ client.email }}</p>
      <p class="text-lg text-gray-700">📞 {{ client.phone || 'N/A' }}</p>
      <!-- Client notes with fallback text -->
      <p class="text-md text-gray-500 italic mt-3">📜 {{ client.notes || 'No notes yet' }}</p>
    </div>

    <!-- Payment Totals Summary Card -->
    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-200 shadow">
      <h3 class="text-xl font-semibold text-indigo-600 mb-2">Totals</h3>
      <!-- Client payments total -->
      <p class="text-lg text-green-600">
        Total Client Paid: ${{ totalClientPaid.toFixed(2) }}
      </p>
      <!-- Insurance payments total -->
      <p class="text-lg text-blue-600">
        Total Insurance Paid: ${{ totalInsurancePaid.toFixed(2) }}
      </p>
    </div>

    <!-- Payment History Section -->
    <div class="bg-white p-8 rounded-xl shadow-md border border-indigo-100">
      <h3 class="text-3xl font-semibold text-indigo-500 mb-6 border-b pb-3">
        Payment History
      </h3>

      <!-- Empty state -->
      <div v-if="payments.length === 0" class="text-lg text-gray-500">
        No payments found.
      </div>

      <!-- Payment list -->
      <div v-else class="space-y-6">
        <!-- Individual payment cards -->
        <div
          v-for="payment in payments"
          :key="payment.id"
          class="p-6 bg-gray-50 rounded-lg border border-gray-200 shadow-sm relative"
        >
          <!-- Edit/Delete buttons (absolute positioned top-right) -->
          <div class="absolute top-3 right-3">
            <button @click="editPayment(payment)" class="text-sm text-blue-600 hover:underline mr-3">Edit</button>
            <button @click="deletePayment(payment.id)" class="text-sm text-red-600 hover:underline">Delete</button>
          </div>
          
          <!-- Payment details -->
          <p class="text-lg text-gray-800 font-semibold mb-2">
            💳 Payment ID: {{ payment.id }}
          </p>
          <p class="text-lg text-green-600">
            Client Paid: ${{ Number(payment.client_paid).toFixed(2) }}
          </p>
          <p class="text-lg text-blue-600">
            Insurance Paid: ${{ Number(payment.insurance_paid).toFixed(2) }}
          </p>
          <!-- Formatted creation date -->
          <p class="text-sm text-gray-400 mt-3">
            🕒 {{ new Date(payment.created_at).toLocaleString() }}
          </p>
        </div>
      </div>
    </div>

    <!-- New Payment Modal -->
    <div
      v-if="showForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
    >
      <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-xl">
        <h3 class="text-2xl font-bold text-purple-600 mb-4">Add New Payment</h3>
        <!-- Payment form component -->
        <NewPaymentForm
          :client-id="clientId"
          @submitted="onPaymentAdded"
          @close="showForm = false"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import api from '../api';
import NewPaymentForm from '../components/NewPaymentForm.vue';

// Get client ID from route params
const route = useRoute();
const clientId = route.params.id;

// Reactive state
const client = ref({});
const payments = ref([]);
const showForm = ref(false);

/**
 * Opens the new payment modal
 */
function openNewPaymentModal() {
  showForm.value = true;
}

/**
 * Fetches payment data for the current client
 */
async function fetchPayments() {
  try {
    const payRes = await api.get('/payments', {
      params: { client_id: clientId }
    });
    payments.value = payRes.data;
  } catch (error) {
    console.error('Error fetching payments:', error);
    // Note: In production, show user notification
  }
}

/**
 * Handles successful payment addition
 * 1. Closes the modal
 * 2. Refreshes payment data
 */
function onPaymentAdded() {
  showForm.value = false;
  fetchPayments();
}

/**
 * Placeholder for edit functionality
 * @param {Object} payment - The payment to edit
 */
function editPayment(payment) {
  alert(`Edit feature not implemented yet for Payment ID ${payment.id}`);
  // TODO: Implement edit functionality
  // Could open an edit modal with the payment data pre-filled
}

/**
 * Deletes a payment after confirmation
 * @param {number} id - The payment ID to delete
 */
async function deletePayment(id) {
  if (confirm('Are you sure you want to delete this payment?')) {
    try {
      await api.delete(`/payments/${id}`);
      fetchPayments();
    } catch (error) {
      console.error('Error deleting payment:', error);
      // Note: In production, show user notification
    }
  }
}

// Computed properties
const totalClientPaid = computed(() => {
  return payments.value.reduce((sum, p) => sum + Number(p.client_paid), 0);
});

const totalInsurancePaid = computed(() => {
  return payments.value.reduce((sum, p) => sum + Number(p.insurance_paid), 0);
});

// Fetch data when component mounts
onMounted(async () => {
  try {
    const clientRes = await api.get(`/clients/${clientId}`);
    client.value = clientRes.data;
    await fetchPayments();
  } catch (error) {
    console.error('Error initializing component:', error);
    // Note: In production, show user notification
  }
});
</script>

<style scoped>
/* 
  Optional CSS tweaks could go here
  Consider adding transition effects for smoother interactions
*/
</style>

<!-- 
  Potential Improvements:
  1. Implement payment editing functionality
  2. Add loading states for API calls
  3. Include error notifications for failed operations
  4. Add payment filtering/sorting options
  5. Implement CSV export for payment history
  6. Add pagination for large payment lists
  7. Include payment method information
  8. Add payment status indicators (cleared, pending, etc.)
  9. Implement bulk payment actions
  10. Add visual charts for payment trends
  11. Include payment attachments/receipts
  12. Add search functionality within payments
  13. Improve accessibility with ARIA attributes
  14. Add keyboard navigation support
  15. Implement undo functionality for deletions
-->