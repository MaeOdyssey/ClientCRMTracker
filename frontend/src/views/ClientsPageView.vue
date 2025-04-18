<!-- 
  ClientsPageView.vue
  Main client management page that serves as a container for the ClientsList component
  This follows the view/component separation pattern where:
  - Views handle routing and page-level layout
  - Components handle specific functionality
-->
<template>
  <!-- 
    Main container with responsive max-width and padding
    - max-w-6xl: Sets maximum width for large screens
    - mx-auto: Centers the container horizontally
    - px-6: Horizontal padding
    - py-10: Vertical padding
  -->
  <div class="max-w-6xl mx-auto px-6 py-10">
    <!-- 
      Page heading with centered text and purple accent color
      - text-3xl: Large text size
      - font-bold: Bold weight
      - text-center: Centers the text
      - text-purple-500: Primary brand color
      - mb-8: Bottom margin for spacing
    -->
    
    <!-- 
      ClientsList component container
      This is where the main client management functionality lives
    -->
    <ClientsList />
  </div>
</template>

<script>
// Import the ClientsList component
import ClientsList from '@/components/ClientsListComponent.vue';

export default {
  name: 'ClientsPage',
  components: {
    ClientsList
  }
};
</script>

<!-- 
  Potential Improvements:
  1. Add a loading state while data is being fetched
  2. Include a search/filter bar at the page level
  3. Add breadcrumb navigation
  4. Implement view toggle (grid/list view)
  5. Add bulk action controls
  6. Include statistics summary cards
  7. Add a "recent activity" section
  8. Implement client import/export functionality
  9. Add help tooltips for new users
  10. Include a welcome message for first-time visitors
  11. Add responsive adjustments for mobile views
  12. Implement dark mode support
  13. Add keyboard shortcuts for common actions
  14. Include a "quick add" floating action button
  15. Add client category filtering
-->