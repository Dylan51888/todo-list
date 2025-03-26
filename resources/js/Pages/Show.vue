<template>
    <div class="min-h-screen bg-gray-100 p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Task Lists</h1>
        <button
          @click="showCreateModal = true"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          + Create New Task List
        </button>
      </div>
  
      <div
        v-for="list in taskList"
        :key="list.id"
        class="bg-white p-4 shadow rounded hover:shadow-lg"
      >
        <h2 class="text-lg font-semibold">
          <input
            type="checkbox"
            :checked="list.checked"
            @change="toggleChecked(list)"
          />
          {{ props.taskList.name }}
        </h2>
        <div class="flex gap-2 mt-3">
          <button @click="editList(list)" class="text-blue-500">Edit</button>
          <button @click="deleteList(list.id)" class="text-red-500">Delete</button>
        </div>
      </div>
  
      <!-- <h1 class="text-2xl font-bold">
        Task List: {{ props.taskList.name }}
        <span v-if="props.taskList.checked" class="text-green-500 ml-2">(✔ Completed)</span>
      </h1> -->
      <div
        v-if="showCreateModal"
        class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center"
      >
        <div class="bg-white p-6 rounded w-[90%] max-w-md">
          <h2 class="text-xl mb-4">
            {{ editingList ? 'Edit Task List' : 'Create New Task List' }}
          </h2>
          <input
            v-model="listName"
            type="text"
            class="border w-full p-2 rounded mb-4"
            placeholder="Task List Name"
          />
          <div class="flex justify-end gap-2">
            <button @click="showCreateModal = false" class="text-gray-600">
              Cancel
            </button>
            <button @click="submitList" class="bg-blue-500 text-white px-4 py-2 rounded">
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'




const props = defineProps({
  taskList: Object  
})

const taskList = ref(props.taskList)
const showCreateModal = ref(false)
const listName = ref('')
const editingList = ref(null)

const toggleChecked = (list) => {
  const newValue = !list.checked
  router.put(route('task-lists.update', list.id), {
    name: list.name,
    checked: newValue
  }, {
    onSuccess: () => {
      list.checked = newValue 
    }
  })
}


const submitList = () => {
  if (!listName.value) return

  if (editingList.value) {

    router.put(route('task-lists.update', editingList.value.id), {
      name: listName.value
    }, {
      onSuccess: () => {
        closeModal()

      }
    })
  } else {

    router.post(route('task-lists.store'), {
      name: listName.value
    }, {
      onSuccess: () => {
        closeModal()
      }
    })
  }
}


const deleteList = (id) => {
  router.delete(route('task-lists.destroy', id))
}


const editList = (list) => {
  editingList.value = list
  listName.value = list.name
  showCreateModal.value = true
}


const closeModal = () => {
  listName.value = ''
  editingList.value = null
  showCreateModal.value = false
}
</script>