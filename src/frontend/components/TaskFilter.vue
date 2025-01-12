<template>
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
            v-model="form.filter"
            @change="filterChange"
          >
            <option selected>Filter By</option>
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
            v-model="form.sort"
            @change="filterChange"
          >
            <option selected>Sort By</option>
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
            v-model="form.sort_order"
            @change="filterChange"
          >
            <option selected>Sort Order</option>
            <option value="asc">ASC</option>
            <option value="desc">DESC</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { debounce } from "lodash";

const emit = defineEmits(["filterTasks"]);

const sortOptions = [
  { key: "title", label: "title" },
  { key: "description", label: "description" },
  { key: "created_at", label: "created at" },
  { key: "completed_at", label: "completed" },
  { key: "priority", label: "priority level" },
  { key: "due_date", label: "due date" },
];

const form = reactive({
  search: "",
  filter: "",
  sort: "",
  sort_order: "",
});

// const {data: tasks} = useFetch()

const filterChange = () => {
  emit("filterTasks", form);
};

watch(
  form.search,
  debounce(function (value) {
    emit("filterTasks", form);
  }, 200)
);
</script>
