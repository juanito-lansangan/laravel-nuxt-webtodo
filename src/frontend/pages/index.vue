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
import { ref } from "vue";

const token = localStorage.getItem("AUTH_TOKEN");

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
