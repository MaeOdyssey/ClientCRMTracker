<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Header -->
    <div class="mb-10">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-indigo-600 tracking-tight">
            🧠 Theracord CRM
          </h1>
          <p class="text-sm text-gray-500">Client Management Dashboard</p>
        </div>
        <button
          @click="showForm = true"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" />
          Add Client
        </button>
      </div>
    </div>

    <NewClientForm
      v-if="showForm"
      @submit="createClient"
      @cancel="showForm = false"
    />

    <EditClientModal
      v-if="showEdit && selectedClient"
      :client="selectedClient"
      @submit="updateClient"
      @cancel="showEdit = false"
    />

    <div class="mb-6 flex flex-col sm:flex-row gap-4">
      <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <SearchIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input
          v-model="search"
          @input="fetchClients"
          placeholder="Search clients..."
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div
        v-for="client in clients"
        :key="client.id"
        class="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200 hover:shadow-lg transition-shadow duration-200"
      >
        <div class="px-4 py-5 sm:p-6">
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 h-10 w-10 bg-indigo-500 text-white font-semibold rounded-full flex items-center justify-center">
              {{ client.first_name.charAt(0) }}{{ client.last_name.charAt(0) }}
            </div>
            <h3 class="text-lg font-medium text-gray-900 leading-6 truncate">
              {{ client.first_name }} {{ client.last_name }}
            </h3>
          </div>

          <div class="mt-4 space-y-2">
            <div class="flex items-center text-sm text-gray-500">
              <MailIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
              <span class="truncate">{{ client.email || 'No email' }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <PhoneIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
              <span>{{ client.phone || 'N/A' }}</span>
            </div>
            <div v-if="client.notes" class="mt-2">
              <p class="text-xs text-gray-500 line-clamp-2">
                <span class="font-medium">Notes:</span> {{ client.notes }}
              </p>
            </div>
          </div>
        </div>

        <div class="px-4 py-4 sm:px-6 bg-gray-50">
          <div class="flex justify-between space-x-3">
            <button
              @click="viewProfile(client)"
              class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-900"
            >
              View profile
            </button>
            <div class="flex space-x-2">
              <button
                @click="editClient(client)"
                class="p-1.5 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                title="Edit"
              >
                <PencilIcon class="h-5 w-5" />
              </button>
              <button
                @click="deleteClient(client.id)"
                class="p-1.5 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                title="Delete"
              >
                <TrashIcon class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { 
  PlusIcon,
  MagnifyingGlassIcon as SearchIcon,
  EnvelopeIcon as MailIcon,
  PhoneIcon,
  PencilSquareIcon as PencilIcon,
  TrashIcon
} from '@heroicons/vue/24/outline';
import api from '../api';
import NewClientForm from './NewClientForm.vue';
import EditClientModal from './EditClientModal.vue';

export default {
  components: {
    NewClientForm,
    EditClientModal,
    PlusIcon,
    SearchIcon,
    MailIcon,
    PhoneIcon,
    PencilIcon,
    TrashIcon
  },
  data() {
    return {
      clients: [],
      search: '',
      showForm: false,
      showEdit: false,
      selectedClient: null,
    };
  },
  methods: {
    async fetchClients() {
      try {
        const [clientsRes, sessionsRes] = await Promise.all([
          api.get('/clients'),
          api.get('/sessions')
        ]);

        this.clients = clientsRes.data.map(client => {
          const clientSessions = sessionsRes.data.filter(
            session => session.client_id === client.id
          );

          return {
            ...client,
            has_sessions: clientSessions.length > 0,
            session_count: clientSessions.length
          };
        });
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
    async createClient(clientData) {
      try {
        await api.post('/clients', clientData);
        this.showForm = false;
        this.fetchClients();
      } catch (error) {
        console.error('Failed to create client:', error);
      }
    },
    async deleteClient(id) {
      if (confirm('Are you sure you want to delete this client?')) {
        try {
          await api.delete(`/clients/${id}`);
          this.fetchClients();
        } catch (error) {
          console.error('Failed to delete:', error);
        }
      }
    },
    editClient(client) {
      this.selectedClient = client;
      this.showEdit = true;
    },
    viewProfile(client) {
      this.$router.push(`/clients/${client.id}/dashboard`);
    },
    async updateClient(updatedClient) {
      try {
        await api.put(`/clients/${updatedClient.id}`, updatedClient);
        this.showEdit = false;
        this.selectedClient = null;
        this.fetchClients();
      } catch (error) {
        console.error('Failed to update client:', error);
      }
    }
  },
  mounted() {
    this.fetchClients();
  }
};
</script>
