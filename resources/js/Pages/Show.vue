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
              :disabled="tasks.length > 10"
            />
          </div>
          <div class="mt-3 mt-sm-0">
            <button
              type="button"
              class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              @click="addTask"
              v-if="tasks.length <= 10"
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
            <!-- <Link 
            :href="route('profile.edit')" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded">
              Back to Dashboard
            </Link> -->
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
<script >
import { ref, onMounted, computed, watch } from "vue";
import { Head, Link } from '@inertiajs/vue3'
window.Echo.join('task-lists')
    .listen('.TaskListUpdated', (e) => {
        fetchTaskLists();
    });
export default {
  name: "HomePage",
  components: {
    Head,
    Link,
  },
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
        if (tasks.value.length > 10) {
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

.btn-dashboard {
  cursor: pointer;
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
