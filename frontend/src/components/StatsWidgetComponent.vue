<template>
  <!-- Card container with white background, shadow, and rounded corners -->
  <div class="bg-white shadow rounded-lg p-6">
    <!-- Widget header -->
    <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Summary</h3>
    
    <!-- Responsive grid layout (1 column on mobile, 3 on medium+ screens) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Total Sessions Stat -->
      <div class="border-l-4 border-red-500 pl-4 py-2">
        <p class="text-sm text-gray-500">Total Sessions</p>
        <!-- Formatted currency value with fixed decimal places -->
        <p class="text-2xl font-bold">${{ totalSessions.toFixed(2) }}</p>
      </div>
      
      <!-- Total Payments Stat -->
      <div class="border-l-4 border-green-500 pl-4 py-2">
        <p class="text-sm text-gray-500">Total Paid</p>
        <!-- Formatted currency value with fixed decimal places -->
        <p class="text-2xl font-bold">${{ totalPayments.toFixed(2) }}</p>
      </div>
      
      <!-- Balance Stat - Dynamic styling based on value -->
      <div 
        class="border-l-4 pl-4 py-2"
        :class="balance >= 0 ? 'border-green-500' : 'border-red-500'"
      >
        <p class="text-sm text-gray-500">Balance</p>
        <!-- 
          Dynamic display:
          - Shows + sign for positive balance
          - Uses Math.abs() to always show positive value
          - Formats currency with 2 decimal places
        -->
        <p class="text-2xl font-bold">
          {{ balance >= 0 ? '+' : '' }}${{ Math.abs(balance).toFixed(2) }}
        </p>
        <!-- Status indicator (Credit/Due) -->
        <p class="text-xs mt-1">
          {{ balance >= 0 ? 'Credit' : 'Due' }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
// Component props with default values
defineProps({
  // Total value of all sessions (sum of fees)
  totalSessions: {
    type: Number,
    default: 0
  },
  // Total value of all payments received
  totalPayments: {
    type: Number,
    default: 0
  },
  // Calculated balance (payments - sessions)
  balance: {
    type: Number,
    default: 0
  }
})
</script>

<!-- 
  Potential Improvements:
  1. Add loading state for asynchronous data
  2. Include trend indicators (up/down arrows with percentages)
  3. Add tooltips explaining each metric
  4. Implement click handlers for drill-down functionality
  5. Add currency formatting that respects locale
  6. Include visual sparklines for historical trends
  7. Make the component more compact for dashboard use
  8. Add animation when values change
  9. Include a refresh button
  10. Add support for different currencies
  11. Improve accessibility with ARIA attributes
  12. Add a print-friendly version
-->