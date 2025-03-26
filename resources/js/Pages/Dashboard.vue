<!-- <template>
    <Head title="Dashboard" />
  
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4 px-6">
            <Link
              :href="route('task-lists.store')"
              method="post"
              as="button"
              class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded"
            >
              Create Todo List
            </Link>
  
            <ul class="divide-y mt-6">
              <li
                v-for="list in taskLists"
                :key="list.id"
                class="px-2 py-1.5 flex justify-between items-center"
              >
                <span>{{ list.name }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import { Head, Link } from '@inertiajs/vue3'
  
  const props = defineProps({
    taskLists: {
      type: Array,
      default: () => []
    }
  })
  
  </script> -->

  <template>
    <Head title="Dashboard" />
  
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Dashboard
        </h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4 px-6">

            <Link
            :href="route('task-lists.store')"
            method="post"
            as="button"
            :data="{ name: 'New List' }"
            class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded"
          >
            Create Todo List
          </Link>
  
            <ul class="divide-y mt-6">
              <li
                v-for="list in taskLists"
                :key="list.id"
                class="px-2 py-1.5 flex justify-between items-center"
              >
                <span>{{ list.name }}</span>
                <div class="space-x-3">
                  <Link
                    :href="route('task-lists.show', list.id)"
                    class="text-green-600 hover:text-green-800"
                  >
                    View
                  </Link>
  

                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import { Head, Link } from '@inertiajs/vue3'
  import { ref, onMounted } from 'vue'
  import axios from 'axios'

  const taskLists = ref([])
  const fetchTaskLists = async () => {
    const res = await axios.get('/task-lists')
    taskLists.value = res.data
    }

    onMounted(() => {
    fetchTaskLists()

    window.Echo.join('task-lists')
        .listen('.TaskListUpdated', (e) => {
        fetchTaskLists()
        })
    })
  const props = defineProps({
    taskLists: {
      type: Array,
      default: () => []
    }
  })
  </script>