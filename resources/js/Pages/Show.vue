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
  
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="list in taskLists"
          :key="list.id"
          class="bg-white p-4 shadow rounded hover:shadow-lg"
        >
          <h2 class="text-lg font-semibold">{{ list.name }}</h2>
          <div class="flex gap-2 mt-3">
            <button @click="editList(list)" class="text-blue-500">Edit</button>
            <button @click="deleteList(list.id)" class="text-red-500">Delete</button>
          </div>
        </div>
      </div>
  

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
  import axios from 'axios'
  
  const taskLists = ref([])
  const showCreateModal = ref(false)
  const listName = ref('')
  const editingList = ref(null)
  
  const fetchTaskLists = async () => {
    const res = await axios.get('/task-lists')
    taskLists.value = res.data
  }
  
  const submitList = async () => {
    if (!listName.value) return
  
    if (editingList.value) {
      await axios.put(`/task-lists/${editingList.value.id}`, {
        name: listName.value
      })
    } else {
      await axios.post('/task-lists', { name: listName.value })
    }
  
    await fetchTaskLists()
    closeModal()
  }
  
  const deleteList = async (id) => {
    await axios.delete(`/task-lists/${id}`)
    await fetchTaskLists()
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
  
  onMounted(fetchTaskLists)
  </script>