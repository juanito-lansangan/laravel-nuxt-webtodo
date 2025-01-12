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
  </section>
</template>

<script setup>
import { ref, watch } from "vue";

const token = "1|PtcPH0RZOHFnEtKpINRXZdBlKHkONOmYXvYag2HCba5fb2a3";

const page = ref(1);

const {
  data: tasks,
  refresh,
  clear,
} = await useFetch(() => `http://localhost:8006/api/tasks?page=${page.value}`, {
  onRequest({ options }) {
    options.headers.set("Authorization", `Bearer ${token}`);
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
