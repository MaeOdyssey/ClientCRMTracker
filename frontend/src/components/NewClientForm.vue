<template>
  <div class="mb-8 p-6 bg-gray-100 rounded-lg shadow">
    <!-- Form Header -->
    <h3 class="text-xl font-bold mb-4 text-gray-800">New Client</h3>

    <form @submit.prevent="submitForm">
      <!-- Grid Layout for Inputs -->
      <div class="grid sm:grid-cols-2 gap-4">
        <!-- First Name -->
        <input 
          v-model="form.first_name" 
          placeholder="First Name" 
          class="p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
          required
        />

        <!-- Last Name -->
        <input 
          v-model="form.last_name" 
          placeholder="Last Name" 
          class="p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
          required
        />

        <!-- Email -->
        <input 
          v-model="form.email" 
          placeholder="Email" 
          type="email"
          class="p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
        />

        <!-- Phone -->
        <input 
          v-model="form.phone" 
          placeholder="Phone" 
          type="tel"
          class="p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
        />
      </div>

      <!-- Notes Field -->
      <textarea 
        v-model="form.notes" 
        placeholder="Notes" 
        class="mt-4 w-full p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
        rows="3"
      ></textarea>

      <!-- Buttons -->
      <div class="mt-4 flex gap-2">
        <button 
          type="submit" 
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          Save
        </button>
        <button 
          @click="$emit('cancel')" 
          type="button" 
          class="text-gray-600 px-4 py-2 hover:underline"
        >
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'NewClientForm',
  data() {
    return {
      form: {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        notes: ''
      }
    };
  },
  methods: {
    submitForm() {
      this.$emit('submit', { ...this.form });

      // Reset form state
      this.form = {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        notes: ''
      };
    }
  }
};
</script>

<!-- 
🧠 Future Enhancements:
1. Add field labels (for accessibility)
2. Show validation errors or success indicators
3. Use a loading state on submit
4. Add max-lengths where appropriate
5. Add ARIA labels or visually hidden screen reader labels
-->
