<template>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Task Lists Dashboard</h1>
  
      <form @submit.prevent="submit" class="mb-6">
        <input
          v-model="form.name"
          type="text"
          placeholder="Enter list name"
          class="border p-2 mr-2"
        />
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">
          Add List
        </button>
      </form>
  
      <ul>
        <li v-for="list in taskLists" :key="list.id" class="mb-2 flex items-center justify-between">
        <input
          type="checkbox"
          :checked="list.checked"
          @change="toggleChecked(list)"
          class="mr-2"
        />
          <span>{{ list.name }}</span>
          <Link :href="route('task-lists.show', list.id)" class="text-blue-500 hover:underline">
            View
          </Link>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    taskLists: {
      type: Array,
      default: () => []
    }
  });
  
  const form = useForm({
    name: ''
  });
  
  function toggleChecked(list) {
    const updatedChecked = !list.checked;
    Inertia.put(route('task-lists.update', list.id), {
        name: list.name,
        checked: updatedChecked
    });
    }

  function submit() {
    form.post(route('task-lists.store'), {
      onSuccess: () => {
        form.reset();
      }
    });
  }
  </script>
  
  <style scoped>

  </style>
