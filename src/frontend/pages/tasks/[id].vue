<template>
  <div>
    <TaskForm
      :task="taskDetails.task.data"
      :tags="taskDetails.tags.data"
      action="edit-task"
    />
  </div>
</template>
<script setup>
const { id } = useRoute().params;

const token = localStorage.getItem("AUTH_TOKEN");

const { data: taskDetails } = await useAsyncData(
  `task-data-${id}`,
  async () => {
    const [task, tags] = await Promise.all([
      useSanctumFetch(`api/tasks/${id}`),
      useSanctumFetch("/api/tags"),
    ]);

    return { task, tags };
  }
);

definePageMeta({
  middleware: ["$auth"],
});
</script>
