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
        <li
          v-for="list in localTaskLists"
          :key="list.id"
          class="mb-2 flex items-center justify-between"
        >
          <input
            type="checkbox"
            :checked="list.checked"
            @change="toggleChecked(list)"
            class="mr-2"
          />
          <span>{{ list.name }}</span>
          <button
            @click="deleteList(list)"
            class="text-red-500 hover:underline ml-2"
          >
            Delete
          </button>
          <Link
            :href="route('task-lists.show', list.id)"
            class="text-blue-500 hover:underline"
          >
            View
          </Link>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref , onMounted} from 'vue';
  import { useForm, Link, router } from '@inertiajs/vue3';
  
  const props = defineProps({
    taskLists: {
      type: Array,
      default: () => [],
    },
  });
  
  const form = useForm({
    name: '',
  });

  const localTaskLists = ref([...props.taskLists]);
  
  function toggleChecked(list) {
    const updatedChecked = !list.checked;

    router.put(route('task-lists.update', list.id), {
      name: list.name,
      checked: updatedChecked,
    });
  }
  
  function deleteList(list) {
  if (confirm('Are you sure you want to delete this list?')) {
    localTaskLists.value = localTaskLists.value.filter(item => item.id !== list.id);
    router.delete(route('task-lists.destroy', list.id), {
      preserveState: true,
      onSuccess: () => {
      }
    });
  }
}
  
function submit() {
  const tempId = Date.now();
  const newList = { id: tempId, name: form.name, checked: false };
  localTaskLists.value.push(newList);
  form.post(route('task-lists.store'), {
    preserveState: true,
    onSuccess: () => {
      form.reset();
    }
  });
}

  onMounted(() => {
    if (window.Echo) {
      window.Echo.channel('task-lists')
        .listen('TaskListUpdated', (event) => {
          if (event.action === 'deleted') {
            localTaskLists.value = localTaskLists.value.filter(item => item.id !== event.taskList.id);
          } else if (event.action === 'created') {
            localTaskLists.value.push(event.taskList);
          } else if (event.action === 'updated') {
            const index = localTaskLists.value.findIndex(item => item.id === event.taskList.id);
            if (index !== -1) {
              localTaskLists.value[index] = event.taskList;
            }
          }
        });
    }
  });

  </script>
  
  <style scoped>
.container {
  max-width: 800px;
}
input[type="text"] {
  border-radius: 4px;
  border: 1px solid #ccc;
  min-width: 200px;
}
button {
  transition: background-color 0.3s ease;
}
button:hover {
  filter: brightness(90%);
}
li {
  background-color: #f9f9f9;
  padding: 12px;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
  </style>