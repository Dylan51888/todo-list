<!-- <template>
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
      v-for="list in taskLists"
      :key="list.id"
      class="bg-white p-4 shadow rounded hover:shadow-lg"
    >
      <h2 class="text-lg font-semibold">
        <input
          type="checkbox"
          :checked="list.checked"
          @change="toggleChecked(list)"
        />
        {{ list.id }}
      </h2>
      <div class="flex gap-2 mt-3">
        <button @click="editList(list)" class="text-blue-500">Edit</button>
        <button @click="deleteList(list.id)" class="text-red-500">Delete</button>
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
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  taskLists: {
    type: Array,
    default: () => []
  }
})


const taskLists = ref(props.taskLists)
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
        router.reload()
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
</script> -->


<!-- Html -->
<template>
  <div class="container d-flex justify-content-center align-items-center">
    <div class="card mt-3 col-6">
      <div class="card-body">
        <h1 class="text-center">Task List</h1>
        <div
          class="d-flex flex-column flex-sm-row justify-content-between mt-3 mt-sm-5"
        >
          <div class="col-sm-9">
            <input
              type="text"
              class="form-control"
              placeholder="Add a new task..."
              v-model="newTask"
              @keyup.enter="addTask"
              :disabled="tasks.length > 4"
            />
          </div>
          <div class="mt-3 mt-sm-0">
            <button
              type="button"
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              @click="addTask"
              v-if="tasks.length <= 4"
            >
              Add
            </button>
            <p v-else class="message">List completed</p>
          </div>
        </div>
        <div class="mt-3 mt-sm-5">
          <div
            class="card item-card mt-2"
            v-for="(task, index) in tasks"
            :key="index"
          >
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <input
                  class="form-check-input"
                  type="checkbox"
                  v-model="task.isDone"
                />
                <div>
                  <p class="fw-semibold">{{ task.description }}</p>
                </div>
                <div>
                  <button
                    type="button"
                    class="text-blue-500"
                    @click="deleteTask(index)"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
          <hr />
          <div class="d-flex flex-column flex-sm-row justify-content-between">
            <div>
              <br>
              <p class="fw-bold" v-show="pendingTasks > 0">
                You have {{ pendingTasks }} pending tasks
              </p>
            </div>
            <div>
              <button
                type="button"
                class="text-blue-500"
                @click="deleteAllTasks"
                v-show="tasks.length > 0"
              >
                Delete All
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from "vue";

export default {
  name: "HomePage",
  setup() {
    const newTask = ref("");
    const tasks = ref([
      { description: "Review pending activities", isDone: false },
      { description: "Attend daily meetings", isDone: false },
    ]);

    const addTask = () => {
      if (!newTask.value) return;
      tasks.value.unshift({
        description: newTask.value,
        isDone: false,
      });
      localStorage.setItem("tasks", JSON.stringify(tasks.value));
      newTask.value = "";
    };

    const deleteTask = (index) => {
      tasks.value.splice(index, 1);
      localStorage.setItem("tasks", JSON.stringify(tasks.value));
    };

    const deleteAllTasks = () => {
      tasks.value = [];
      localStorage.removeItem("tasks");
    };

    const pendingTasks = computed(() => {
      return tasks.value.filter((x) => x.isDone === false).length;
    });

    watch(
      tasks,
      () => {
        if (tasks.value.length > 4) {
          alert('You have reached the maximum number of possible tasks (5)');
        }
      },
      { deep: true }
    );

    onMounted(() => {
      if (localStorage.tasks) {
        tasks.value = JSON.parse(localStorage.getItem("tasks")) || [];
      }
    });

    return {
      newTask,
      tasks,
      addTask,
      deleteTask,
      deleteAllTasks,
      pendingTasks,
    };
  },
};
</script>

<style scoped>
.card-body h1 {
  color: black;
}


.btn-danger {
  background-color: #5f8dba;
  border: transparent;
}

.message {
  color: green;
  font-weight: bold;
}

</style>
