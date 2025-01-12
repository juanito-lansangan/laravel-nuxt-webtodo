<template>
  <TaskForm :tags="tags" action="create-task" />
</template>
<script setup>
const token = localStorage.getItem("AUTH_TOKEN");

const page = ref(1);

const {
  data: tags,
  refresh,
  clear,
} = await useFetch(() => `http://localhost:8006/api/tags`, {
  onRequest({ options }) {
    options.headers.set("Authorization", `Bearer ${token}`);
  },
});
</script>
