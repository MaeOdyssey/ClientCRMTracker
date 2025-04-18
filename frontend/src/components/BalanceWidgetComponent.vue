<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Total Sessions -->
      <div class="bg-white shadow rounded-lg p-4 flex items-center">
        <div class="bg-indigo-100 text-indigo-600 p-2 rounded-full mr-4">
          <CalendarIcon class="w-5 h-5" />
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Sessions</p>
          <p class="text-lg font-bold text-gray-900">{{ totalSessions }}</p>
        </div>
      </div>
  
      <!-- Total Charged -->
      <div class="bg-white shadow rounded-lg p-4 flex items-center">
        <div class="bg-purple-100 text-purple-600 p-2 rounded-full mr-4">
          <CurrencyDollarIcon class="w-5 h-5" />
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Charged</p>
          <p class="text-lg font-bold text-gray-900">${{ totalCharged.toFixed(2) }}</p>
        </div>
      </div>
  
      <!-- Total Paid -->
      <div class="bg-white shadow rounded-lg p-4 flex items-center">
        <div class="bg-green-100 text-green-600 p-2 rounded-full mr-4">
          <BanknotesIcon class="w-5 h-5" />
        </div>
        <div>
          <p class="text-sm text-gray-500">Total Paid</p>
          <p class="text-lg font-bold text-gray-900">${{ totalPaid.toFixed(2) }}</p>
        </div>
      </div>
  
      <!-- Remaining Balance -->
      <div class="bg-white shadow rounded-lg p-4 flex items-center">
        <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full mr-4">
          <ExclamationCircleIcon class="w-5 h-5" />
        </div>
        <div>
          <p class="text-sm text-gray-500">Remaining Balance</p>
          <p class="text-lg font-bold text-gray-900">${{ balanceRemaining.toFixed(2) }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import { CalendarIcon, CurrencyDollarIcon, BanknotesIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline'
  
  const props = defineProps({
    totalSessions: Number,
    totalCharged: Number,
    totalClientPaid: Number,
    totalInsurancePaid: Number
  })
  
  const totalPaid = computed(() => props.totalClientPaid + props.totalInsurancePaid)
  const balanceRemaining = computed(() => props.totalCharged - totalPaid.value)
  </script>
  