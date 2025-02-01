<template>
  <section class="container">
    <div class="task-filter">
      <div class="task-filter__search-wrapper">
        <label for="search" class="task-filter__search">
          <div class="task-filter__search-input-group">
            <div class="task-filter__search-icon">
              <Icon
                name="material-symbols:search"
                style="color: gray"
                size="24"
              />
            </div>
            <input
              class="task-filter__search-input"
              placeholder="Search Task"
              type="text"
              name="search"
              id="search"
              autocomplete="off"
              v-model="search"
            />
          </div>
        </label>
      </div>

      <div class="task-filter__select-wrapper">
        <div class="task-filter__select">
          <div class="task-filter__select-item">
            <select
              id="filterBy"
              class="form-select task-filter__filter-fields"
              v-model="filterField"
            >
              <option value="">Filter By</option>
              <option value="completed_at">Completed</option>
              <option value="priority">Priority</option>
              <option value="due_date">Due Date</option>
              <option value="archived_at">Archive</option>
            </select>
          </div>

          <div
            class="task-filter__select-item"
            v-if="filterField === 'priority'"
          >
            <select
              id="filterByPriority"
              class="form-select task-filter__filter-fields"
              v-model="filterFieldPriority"
              @change="refreshTasks"
            >
              <option value="">Select Priority</option>
              <option value="4">Urgent</option>
              <option value="3">High</option>
              <option value="2">Normal</option>
              <option value="1">Low</option>
            </select>
          </div>

          <div class="task-filter__select-item task-filter__daterange" v-else>
            <VueDatePicker
              placeholder="Select Date Range"
              range
              format="MM/dd/yyyy"
              @update:model-value="handleDate"
              v-model="dateRange"
            />
          </div>

          <div class="task-filter__select-item">
            <select
              id="sortOrder"
              class="form-select task-filter__sort-order"
              v-model="sortOrder"
              @change="refreshTasks"
            >
              <option value="">Sort Order</option>
              <option value="asc">ASC</option>
              <option value="desc">DESC</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="tasks-container">
      <div class="empty-tasks" v-if="tasks.data.length === 0">
        <h2>No Tasks Found</h2>
        <p>You can add tasks here</p>
        <NuxtLink class="btn btn-sm" to="/tasks/create">Add New Task</NuxtLink>
      </div>
      <div class="task-cards" v-else>
        <TaskCard
          v-for="task in tasks.data"
          :key="task.id"
          :task="task"
          @refreshTasks="refreshTasks"
        />
      </div>
    </div>

    <Pagination :pageData="tasks" @changePage="refetch" />
    <Preloader v-if="showPreloader" />
  </section>
</template>

<script setup>
import { ref, reactive } from "vue";
import { debounce } from "lodash";

const page = ref(1);
const showPreloader = ref(false);

const search = ref("");
const sortOrder = ref("");
const filterField = ref("");
const filterFieldPriority = ref("");
const dateFrom = ref("");
const dateTo = ref("");
const dateRange = ref("");

const { data: tasks, refresh } = await useAsyncData(
  `task-data-list`,
  async () => {
    return await useSanctumFetch(
      `api/tasks/?page=${page.value}&search=${search.value}&date_filter=${filterField.value}&date_from=${dateFrom.value}&date_to=${dateTo.value}&priority=${filterFieldPriority.value}&sort_by=${filterField.value}&sort_order=${sortOrder.value}`
    );
  }
);

console.log(tasks);

definePageMeta({
  middleware: ["$auth"],
});

watch(
  search,
  debounce(function (value) {
    refresh();
  }, 200)
);

// Refetch tasks when page changes
const refetch = async (pageNumber) => {
  showPreloader.value = true;
  if (pageNumber) {
    page.value = pageNumber;
  }
  await refresh();
  showPreloader.value = false;
};

const refreshTasks = () => {
  console.log("reload tasks");
  refetch();
};

const handleDate = (modelData) => {
  const from = new Date(modelData[0]);
  const to = new Date(modelData[1]);
  dateFrom.value = from.toISOString().split("T")[0];
  dateTo.value = to.toISOString().split("T")[0];

  refetch();
};
</script>

<style>
.empty-tasks {
  height: 350px;
  width: 73%;
  border: 2px solid #ddd;
  border-radius: 25px;
  margin: auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.tasks-container {
  min-height: 450px;
}
</style>
