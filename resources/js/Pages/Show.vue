<template>
  <div class="container d-flex justify-content-center align-items-center">
    <div class="card mt-3 col-6">
      <div class="card-body">
        <h1 class="text-center">Task List of {{ taskList.name }}</h1>
        <div class="d-flex flex-column flex-sm-row justify-content-between mt-3 mt-sm-5">
          <div class="col-sm-9">
            <input
              type="text"
              class="form-control"
              placeholder="Add a new task..."
              v-model="newTask"
              @keyup.enter="addTask"
              :disabled="tasks.length >= maxTasks"
            />
          </div>
          <div class="mt-3 mt-sm-0">
            <button
              type="button"
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              @click="addTask"
              v-if="tasks.length < maxTasks"
            >
              Add
            </button>
            <p v-else class="message">List completed</p>
          </div>
        </div>
        <div class="mt-3 mt-sm-5">
          <div
            class="card item-card mt-2"
            v-for="task in tasks"
            :key="task.id"
          >
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <input
                  class="form-check-input"
                  type="checkbox"
                  v-model="task.isDone"
                  @change="updateTask(task)"
                />
                <div>
                  <p class="fw-semibold">{{ task.name }}</p>
                </div>
                <div>
                  <button
                    type="button"
                    class="text-blue-500"
                    @click="deleteTask(task.id)"
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
            <Link
              :href="route('dashboard')"
              as="button"
              class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded btn-dashboard"
            >
              Back to Dashboard
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';

const props = defineProps({
  taskLists: {
    type: Object,
    default: () => ({ tasks: [], name: '' })
  }
});

const taskList = ref({ ...props.taskLists });
const maxTasks = 10;
const tasks = ref(taskList.value.tasks || []);
const newTask = ref('');

function addTask() {
  if (!newTask.value) return;
  const tempId = Date.now();
  const newTaskItem = { id: tempId, name: newTask.value, isDone: false };
  tasks.value.push(newTaskItem);
  
  router.post(
    route('task-lists.tasks.store', taskList.value.id),
    {
      name: newTask.value,
      isDone: false,
    },
    {
      preserveState: true,
      onSuccess: () => {
        newTask.value = '';
      }
    }
  );
}

function updateTask(task) {
  router.put(
    route('task-lists.update', taskList.value.id),
    {
      name: task.name,
      isDone: task.isDone,
    },
    {
      preserveState: true,
      onSuccess: () => {}
    }
  );
}

function deleteTask(id) {

  tasks.value = tasks.value.filter(task => task.id !== id);
  router.delete(
    route('tasks.destroy', id),
    {},
    {
      preserveState: true,
      onSuccess: () => {
      }
    }
  );
}

function deleteAllTasks() {
  router.delete(
    route('task-lists.tasks.destroy', taskList.value.id),
    {},
    {
      preserveState: true,
      onSuccess: () => {}
    }
  );
}

const pendingTasks = computed(() => tasks.value.filter(task => !task.isDone).length);

onMounted(() => {
  if (window.Echo) {
    window.Echo.channel('task-lists')
      .listen('TaskListUpdated', (event) => {
        if (event.taskList.id === taskList.value.id) {
          taskList.value = event.taskList;
          tasks.value = event.taskList.tasks || [];
        }
      });
  }
});
</script>

<style scoped>
.card-body h1 {
  color: #1f2937;
  font-size: 1.75rem;
  font-weight: 600;
}
.btn-dashboard {
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.btn-dashboard:hover {
  background-color: #4b5563;
}
.message {
  color: #10b981;
  font-weight: bold;
  font-size: 1rem;
}
.card.item-card {
  border-radius: 6px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
.card.item-card:hover {
  background-color: #f3f4f6;
}
input.form-check-input {
  cursor: pointer;
}
</style>