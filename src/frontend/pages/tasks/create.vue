<template>
  <TaskForm :tags="data" action="create-task" />
</template>
<script setup>
const token = localStorage.getItem("AUTH_TOKEN");

const page = ref(1);

const { data, error } = await useSanctumFetch("/api/tags");

definePageMeta({
  middleware: ["$auth"],
});
</script>
