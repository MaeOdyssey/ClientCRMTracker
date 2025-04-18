<template>
  <!-- Main container with responsive max-width and padding -->
  <div class="max-w-5xl mx-auto px-6 py-10">
    <!-- Page heading with centered text and indigo accent color -->
    <h2 class="text-3xl font-bold text-center text-indigo-500 mb-8">Payments</h2>

    <!-- Payment Form Component -->
    <NewPaymentForm @submitted="fetchPayments" />

    <!-- Client Filter Section (shown when filtered) -->
    <div v-if="filteredClient" class="text-center mb-6">
      <h3 class="text-xl font-semibold text-gray-700">
        Showing payments for:
        <span class="text-purple-600">
          {{ filteredClient.first_name }} {{ filteredClient.last_name }}
        </span>
      </h3>
      <!-- Clear filter button -->
      <button
        @click="clearFilter"
        class="mt-2 px-4 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition"
      >
        🔄 Show All Payments
      </button>
    </div>

    <!-- Payment List Section -->
    <div class="mt-10 space-y-4">
      <!-- Payment Card - Rendered for each payment -->
      <div
        v-for="payment in payments"
        :key="payment.id"
        class="p-4 bg-white rounded-lg shadow border border-gray-200"
      >
        <!-- Payment details with emoji indicators -->
        <p class="text-gray-700">
          💳 Payment ID: <span class="font-semibold">{{ payment.id }}</span>
        </p>
        <p>👤 {{ getClientName(payment.client_id) }}</p>
        <p>💵 Client Paid: ${{ payment.client_paid }}</p>
        <p>🏥 Insurance Paid: ${{ payment.insurance_paid }}</p>
        <!-- Formatted creation date -->
        <p class="text-sm text-gray-400">🕒 {{ formatDate(payment.created_at) }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../api';
import NewPaymentForm from '../components/NewPaymentForm.vue';

export default {
  components: { NewPaymentForm },

  data() {
    return {
      payments: [],  // Stores payment records
      clients: []    // Stores client data for name resolution
    };
  },

  computed: {
    /**
     * Returns the currently filtered client (if any)
     * Based on the 'client' query parameter in the URL
     */
    filteredClient() {
      const clientId = this.$route.query.client;
      return this.clients.find(c => c.id == clientId);
    }
  },

  methods: {
    /**
     * Fetches payments from API
     * @param {number|null} clientId - Optional client ID filter
     */
    async fetchPayments(clientId = null) {
      try {
        const res = await api.get('/payments', {
          params: clientId ? { client_id: clientId } : {}
        });
        this.payments = res.data;
      } catch (err) {
        console.error('Error fetching payments:', err);
        // Note: In production, show user notification
      }
    },

    /**
     * Fetches all clients for name resolution
     */
    async fetchClients() {
      try {
        const res = await api.get('/clients');
        this.clients = res.data;
      } catch (err) {
        console.error('Error fetching clients:', err);
        // Note: In production, show user notification
      }
    },

    /**
     * Clears the client filter by removing the query parameter
     */
    clearFilter() {
      this.$router.push({ path: '/payments' });
    },

    /**
     * Gets client name by ID
     * @param {number} id - Client ID
     * @returns {string} Client name or fallback text
     */
    getClientName(id) {
      const client = this.clients.find(c => c.id === id);
      return client ? `${client.first_name} ${client.last_name}` : `Client #${id}`;
    },

    /**
     * Formats date string to local format
     * @param {string} dateString - ISO date string
     * @returns {string} Formatted date
     */
    formatDate(dateString) {
      return new Date(dateString).toLocaleString();
    }
  },

  /**
   * Lifecycle hook - Fetches initial data when component mounts
   * Checks for client filter in URL query parameters
   */
  mounted() {
    const clientId = this.$route.query.client;
    this.fetchPayments(clientId);
    this.fetchClients();
  }
};
</script>

<!-- 
  Potential Improvements:
  1. Add loading states for API calls
  2. Implement error notifications for failed requests
  3. Add pagination for large payment lists
  4. Include sorting functionality (by date, amount)
  5. Add search/filter capabilities
  6. Implement CSV export functionality
  7. Add payment editing capability
  8. Include visual charts for payment trends
  9. Add bulk payment actions
  10. Improve currency formatting (decimal places, commas)
  11. Add responsive design enhancements
  12. Implement dark mode support
  13. Add keyboard navigation
  14. Include payment method indicators
  15. Add receipt/attachment viewing
-->