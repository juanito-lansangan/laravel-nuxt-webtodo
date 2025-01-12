<template>
  <section class="container">
    <TaskFilter />
    <div class="task-cards">
      <TaskCard
        v-for="task in tasks.data"
        :key="task.id"
        :task="task"
        @refreshTasks="refreshTasks"
      />
    </div>
    <!-- {{ tasks.data }} -->

    <Pagination :pageData="tasks" @changePage="refetch" />
    <Preloader v-if="showPreloader" />
  </section>
</template>

<script setup>
import { ref } from "vue";

const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";

const page = ref(1);
const showPreloader = ref(false);

const {
  data: tasks,
  refresh,
  clear,
} = await useFetch(() => `http://localhost:8006/api/tasks?page=${page.value}`, {
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
});

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
</script>
