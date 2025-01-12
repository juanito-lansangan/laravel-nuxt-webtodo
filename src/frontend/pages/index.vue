<template>
  <section class="container">
    <!-- <TaskFilter @filterTasks="filterTasks" /> -->
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
              @change="filterChange"
            >
              <option value="">Filter By</option>
              <option value="completed_at">Completed</option>
              <option value="priority">Priority</option>
              <option value="due_date">Due Date</option>
              <option value="archive_at">Archive</option>
            </select>
          </div>

          <div class="task-filter__select-item">
            <select
              id="sortBy"
              class="form-select task-filter__sort"
              @change="filterChange"
              v-model="sortField"
            >
              <option value="">Sort By</option>
              <option value="created_at">Created</option>
              <option value="completed_at">Completed</option>
              <option value="priority">Priority</option>
              <option value="due_date">Due Date</option>
            </select>
          </div>

          <div class="task-filter__select-item">
            <select
              id="sortOrder"
              class="form-select task-filter__sort-order"
              v-model="sortOrder"
              @change="filterChange"
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
    <!-- <pre>

        {{ user }}
    </pre>
    <pre>
        {{ isLoggedIn }}
    </pre> -->

    <Pagination :pageData="tasks" @changePage="refetch" />
    <Preloader v-if="showPreloader" />
  </section>
</template>

<script setup>
// const { isLoggedIn, user } = useSanctum();
import { ref, reactive } from "vue";
import { debounce } from "lodash";
const token = localStorage.getItem("AUTH_TOKEN");

const page = ref(1);
const showPreloader = ref(false);

const sortOptions = [
  { key: "title", label: "title" },
  { key: "description", label: "description" },
  { key: "created_at", label: "created at" },
  { key: "completed_at", label: "completed" },
  { key: "priority", label: "priority level" },
  { key: "due_date", label: "due date" },
];

const search = ref("");
const sortField = ref("");
const sortOrder = ref("");

const {
  data: tasks,
  refresh,
  clear,
} = await useFetch(
  () =>
    `http://localhost:8006/api/tasks?page=${page.value}&search=${search.value}&sort_by=${sortField.value}&sort_order=${sortOrder.value}`,
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

const filterChange = () => {
  //   if (type === 'sortfield') {
  console.log(sortField.value);
  //   }
  refresh();
};
</script>
