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
    <div class="task-cards">
      <TaskCard
        v-for="task in tasks.data"
        :key="task.id"
        :task="task"
        @refreshTasks="refreshTasks"
      />
    </div>

    <Pagination :pageData="tasks" @changePage="refetch" />
    <Preloader v-if="showPreloader" />
  </section>
</template>

<script setup>
import { ref, reactive } from "vue";
import { debounce } from "lodash";
const token = localStorage.getItem("AUTH_TOKEN");

const page = ref(1);
const showPreloader = ref(false);

const search = ref("");
const sortOrder = ref("");
const filterField = ref("");
const filterFieldPriority = ref("");
const dateFrom = ref("");
const dateTo = ref("");
const dateRange = ref("");

const baseUrl = "http://localhost:8006/api/tasks";

const {
  data: tasks,
  refresh,
  clear,
} = await useFetch(
  () =>
    `${baseUrl}?page=${page.value}&search=${search.value}&date_filter=${filterField.value}&dateFrom=${dateFrom.value}&dateTo=${dateTo.value}&priority=${filterFieldPriority.value}&sort_by=${filterField.value}&sort_order=${sortOrder.value}`,
  {
    onRequest({ options }) {
      options.headers.set("Authorization", `Bearer ${token}`);
      showPreloader.value = true;
    },
    onResponse({ request, response, options }) {
      showPreloader.value = false;
    },
    onResponseError({ request, response, options }) {
      showPreloader.value = false;
    },
  }
);

watch(
  search,
  debounce(function (value) {
    refresh();
  }, 200)
);

// Refetch tasks when page changes
const refetch = (pageNumber) => {
  if (pageNumber) {
    page.value = pageNumber;
  }
  refresh();
};

const refreshTasks = () => {
  refresh();
};

const handleDate = (modelData) => {
  const dateFrom = new Date(modelData[0]);
  const dateTo = new Date(modelData[1]);
  dateFrom.value = dateFrom.toISOString().split("T")[0];
  dateTo.value = dateTo.toISOString().split("T")[0];
  console.log([dateFrom.value, dateTo.value]);
  refresh();
};
</script>
