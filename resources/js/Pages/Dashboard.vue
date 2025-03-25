<!-- resources/js/Pages/Dashboard.vue -->
<template>
    <Head title="Dashboard" />
  
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-4 px-6">
            <!-- If you want a "Create Todo List" button that calls task-lists.store -->
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
                <!-- If you want Edit/Delete buttons, add them here -->
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
  
  // Props from the route => Inertia::render('Dashboard', ['taskLists' => $taskLists])
  const props = defineProps({
    taskLists: {
      type: Array,
      default: () => []
    }
  })
  
  // If you don't need real-time features or Echo, remove them entirely.
  </script>