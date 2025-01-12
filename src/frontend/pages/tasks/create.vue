<template>
  <!-- <TaskForm
      :task="taskDetails.task.data"
      :tags="taskDetails.tags"
      action="create-task"
    /> -->
  <TaskForm :tags="tags" action="create-task" />
</template>
<script setup>
const token = "1|Jb86zkblWTTr8IsfVQsc26ZgrcoASiVDsUxsXhkAf1d7db29";

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
