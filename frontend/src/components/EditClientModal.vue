<template>
  <!-- 
    Modal Overlay:
    - Fixed positioning covering the entire viewport
    - Semi-transparent black background
    - Centers content both horizontally and vertically
    - High z-index to ensure it appears above other content
  -->
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <!-- 
      Modal Container:
      - White background with padding and rounded corners
      - Shadow for depth effect
      - Responsive width (full width on small screens, max-width on larger screens)
    -->
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
      <!-- Modal Header -->
      <h3 class="text-xl font-bold mb-4 text-gray-800">Edit Client</h3>
      
      <!-- 
        Edit Form:
        - Uses submit.prevent to prevent default form submission
        - Grid layout for consistent spacing between fields
      -->
      <form @submit.prevent="submitUpdate">
        <div class="grid grid-cols-1 gap-4">
          <!-- 
            Form Fields:
            - Each field is bound to the form object using v-model
            - Simple styling with padding, rounded borders, and consistent height
            - Note: Consider adding proper HTML input types (email, tel) and validation
          -->
          <input 
            v-model="form.first_name" 
            placeholder="First Name" 
            class="p-2 rounded border" 
          />
          <input 
            v-model="form.last_name" 
            placeholder="Last Name" 
            class="p-2 rounded border" 
          />
          <input 
            v-model="form.email" 
            placeholder="Email" 
            class="p-2 rounded border" 
          />
          <input 
            v-model="form.phone" 
            placeholder="Phone" 
            class="p-2 rounded border" 
          />
          <textarea 
            v-model="form.notes" 
            placeholder="Notes" 
            class="p-2 rounded border"
          ></textarea>
        </div>

        <!-- 
          Form Actions:
          - Flex layout to align buttons to the right
          - Gap for consistent spacing between buttons
          - Cancel button emits 'cancel' event
          - Save button triggers form submission
        -->
        <div class="mt-4 flex justify-end gap-2">
          <button 
            type="button" 
            @click="$emit('cancel')" 
            class="text-gray-600 px-4 py-2"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EditClientModal',
  props: {
    // Client data object passed from parent component
    client: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      // Local form state - initialized as empty to prevent undefined errors
      form: {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        notes: ''
      }
    };
  },
  mounted() {
    // When component mounts, copy client props to local form state
    // Using spread operator to create a shallow copy
    if (this.client) {
      this.form = { ...this.client };
    }
  },
  methods: {
    /**
     * Handles form submission
     * Emits the updated client data to parent component
     * Parent component is responsible for actual API call
     */
    submitUpdate() {
      // Emit the form data as a new object to prevent reference issues
      this.$emit('submit', { ...this.form });
    }
  }
};
</script>

<!-- 
  Potential Improvements (not implemented):
  1. Input validation
  2. Loading state during submission
  3. Better accessibility (labels, ARIA attributes)
  4. More specific input types (email, tel)
  5. Error message display
-->